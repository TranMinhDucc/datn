<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    /** Recalc các dòng refund auto (is_manual_amount = 0) trước khi tạo phiếu */
    private function recalcAuto(ReturnRequest $rr): void
    {
        if (Refund::where('return_request_id', $rr->id)->whereIn('status', ['pending', 'done'])->exists()) {
            return;
        }

        $rr->loadMissing(['items.orderItem', 'items.actions']);

        foreach ($rr->items as $it) {
            $oi = $it->orderItem;
            if (!$oi) {
                continue;
            }

            $unit = $it->unit_price_paid ?? round(($oi->total_price ?? 0) / max(1, (int)($oi->quantity ?? 1)), 2);

            foreach ($it->actions->where('action', 'refund') as $act) {
                if ($act->is_manual_amount) continue;

                $newAmt = round($unit * (int)$act->quantity, 2);
                if (abs($newAmt - (float)$act->refund_amount) >= 0.01) {
                    $act->refund_amount = $newAmt;
                    $act->save();
                }
            }
        }

        $sum = (float) $rr->items->sum(
            fn($it) => (float) ($it->actions?->where('action', 'refund')->sum('refund_amount') ?? 0)
        );

        $rr->total_refund_amount = round($sum, 2);
        $rr->save();
    }

    public function createFromRR(Request $request, $rr)
    {
        $returnRequest = ReturnRequest::with(['order', 'items.actions'])->findOrFail($rr);
        $order = $returnRequest->order;
        // ✅ Check đủ action cho mọi item
        $hasItemWithoutAction = $returnRequest->items->contains(fn($it) => $it->actions->isEmpty());
        if ($hasItemWithoutAction) {
            return back()->with('error', 'Bạn phải xử lý tất cả sản phẩm (tạo action hoàn/đổi/từ chối) trước khi tạo phiếu hoàn.');
        }
        // ✅ Chặn nếu chưa QC hết
      $allActions = $returnRequest->items->flatMap(fn($it) => $it->actions);

// Bắt buộc tất cả action refund/exchange đều phải có qc_status hợp lệ
$hasUnqc = $allActions->contains(
    fn($ac) =>
        in_array($ac->action, ['refund', 'exchange'])
        && !in_array($ac->qc_status, ['passed','passed_import','passed_noimport','failed'])
);

if ($hasUnqc || $allActions->isEmpty()) {
    return back()->with('error', 'Bạn phải QC toàn bộ sản phẩm (mọi action hoàn/đổi đều phải QC) trước khi tạo phiếu hoàn.');
}


        // ✅ Check mỗi item đã xử lý đủ số lượng
        foreach ($returnRequest->items as $it) {
            $itemQty = (int) ($it->quantity ?? $it->orderItem->quantity);
            $sumActionQty = (int) $it->actions->sum('quantity');

            if ($sumActionQty < $itemQty) {
                return back()->with('error', "Sản phẩm {$it->orderItem->product_name} chưa xử lý đủ số lượng ({$sumActionQty}/{$itemQty}).");
            }
        }

        if ($hasUnqc || $allActions->isEmpty()) {
            return back()->with('error', 'Bạn phải QC tất cả sản phẩm trước khi tạo phiếu hoàn.');
        }

        // Số tiền mặc định = tổng refund_amount chỉ của action refund QC đạt
        $defaultAmount = (float) $returnRequest->items->sum(function ($it) {
            return (float) ($it->actions
                ?->where('action', 'refund')
                ->filter(fn($act) => str_starts_with($act->qc_status, 'passed'))
                ->sum('refund_amount') ?? 0);
        });



        $amount = (float) ($request->input('amount') ?? $defaultAmount);
        if ($amount <= 0) {
            return back()->with('error', 'Số tiền hoàn không hợp lệ.');
        }

        // Không cho tạo trùng phiếu (pending/done) cho cùng RR
        $exists = Refund::where('return_request_id', $returnRequest->id)
            ->whereIn('status', ['pending', 'done'])
            ->exists();
        if ($exists) {
            return back()->with('error', 'Yêu cầu này đã có phiếu hoàn (pending/done).');
        }

        // Tạo phiếu hoàn
        $refund = Refund::create([
            'order_id'          => $order->id,
            'return_request_id' => $returnRequest->id,
            'user_id'           => $order->user_id,
            'amount'            => $amount,
            'currency'          => 'VND',
            'method' => $request->input('method', 'bank'),
            'status'            => 'pending',
            'note'   => $request->input('note', 'Hoàn tiền cho yêu cầu #' . $returnRequest->id),
            'breakdown'         => json_encode([
                'source' => 'return_request',
                'rr_id'  => $returnRequest->id,
            ]),
            'processed_by'      => auth()->id(),
        ]);

        // Cập nhật trạng thái đơn
        if ($returnRequest->items->contains(fn($it) => $it->qty_exchange > 0)) {
            $order->update(['status' => 'exchange_and_refund_processing']);
        } else {
            $order->update(['status' => 'refund_processing']);
        }

        return back()->with(
            'success',
            '✅ Đã tạo phiếu hoàn #' . $refund->id .
                ' (' . number_format($refund->amount, 0, ',', '.') . 'đ).'
        );
    }

    public function markDone(Refund $refund, Request $req)
    {
        $data = $req->validate([
            'bank_ref'       => ['required', 'string', 'max:190'],
            'transferred_at' => ['nullable', 'date'],
            'method'         => ['nullable', 'string', 'max:190'],
            'note'           => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::transaction(function () use ($refund, $data) {
                // 0) Lock Order để tính toán an toàn
                $order = \App\Models\Order::lockForUpdate()->find($refund->order_id);
                if (!$order) {
                    throw new \Exception("Không tìm thấy đơn hàng #{$refund->order_id}");
                }

                // 1) Tính tổng tiền đã hoàn trước đó (DONE)
                $totalRefundedBefore = Refund::where('order_id', $order->id)
                    ->where('status', 'done')
                    ->sum('amount');

                $newTotal = $totalRefundedBefore + $refund->amount;

                // 🚨 Chặn trường hợp hoàn quá số tiền đơn hàng
                if ($newTotal > $order->total_amount) {
                    throw new \Exception("❌ Số tiền hoàn vượt quá tổng giá trị đơn hàng #{$order->id}");
                }

                // 2) Update Refund (chỉ khi hợp lệ)
                $refund->update([
                    'status'         => 'done',
                    'bank_ref'       => $data['bank_ref'],
                    'transferred_at' => $data['transferred_at'] ?? now(),
                    'method'         => $data['method'] ?? $refund->method,
                    'note'           => $data['note'] ?? $refund->note,
                    'processed_by'   => auth()->id(),
                ]);

                // 3) Update Return Request
                $rr = ReturnRequest::with(['items.actions'])
                    ->lockForUpdate()
                    ->findOrFail($refund->return_request_id);

                $exQty = (int) $rr->items->sum(
                    fn($it) => (int) ($it->actions?->where('action', 'exchange')->sum('quantity') ?? 0)
                );
                $hasExchangeOrder = !empty($rr->exchange_order_id);

                $nextStatus = ($exQty > 0 && !$hasExchangeOrder) ? 'approved' : 'refunded';

                $rr->update([
                    'status'      => $nextStatus,
                    'refunded_at' => now(),
                ]);

                // 4) Update Order
                if ($newTotal >= $order->total_amount) {
                    $order->payment_status = 'refunded'; // Hoàn toàn bộ
                } elseif ($newTotal > 0) {
                    $order->payment_status = 'partially_refunded'; // Hoàn một phần
                } else {
                    $order->payment_status = 'paid'; // chưa hoàn gì
                }

                $order->refunded_at = $order->payment_status === 'refunded'
                    ? ($order->refunded_at ?? now())
                    : $order->refunded_at;

                // Trạng thái đơn hàng
                if ($exQty > 0) {
                    if ($hasExchangeOrder) {
                        $order->status = 'exchanged_and_refunded';
                    } else {
                        $order->status = 'exchange_and_refund_processing';
                    }
                } else {
                    $order->status = $order->payment_status === 'partially_refunded'
                        ? 'refund_processing'
                        : 'refunded';
                }

                $order->save();

                // 🔹 Notify user
                $user = $order->user;
                if ($user) {
                    $user->notify(new \App\Notifications\RefundCompleted($refund));
                }
            });

            return back()->with('success', 'Đã hoàn tiền thành công cho khách hàng');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    private function ensureAllQcDone(ReturnRequest $rr): bool
    {
        $actions = $rr->items->flatMap(fn($it) => $it->actions);

        return $actions->isNotEmpty()
            && $actions->every(fn($ac) => in_array($ac->qc_status, [
                'passed_import',
                'passed_noimport',
                'failed'
            ]));
    }
}
