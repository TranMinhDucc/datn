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

    /** Tạo phiếu hoàn (snapshot) từ ReturnRequest */
    public function createFromRR(ReturnRequest $rr, Request $req)
    {
        // Không cho tạo nếu đã có phiếu pending/done
        if (Refund::where('return_request_id', $rr->id)->whereIn('status', ['pending', 'done'])->exists()) {
            return back()->with('error', 'Đã có phiếu hoàn cho yêu cầu này.');
        }

        // Tính lại các dòng auto trước khi snapshot
        $this->recalcAuto($rr);

        // Nạp quan hệ để lấy user_id khách
        $rr->loadMissing([
            'items.actions',
            'order:id,user_id',
            'order.user:id',
        ]);

        // Tổng hoàn theo các dòng refund
        $itemRefund = (float) $rr->items->sum(
            fn($it) => (float) ($it->actions?->where('action', 'refund')->sum('refund_amount') ?? 0)
        );

        $breakdown = [
            'item_refund' => $itemRefund,
            'ship_refund' => (float) ($rr->ship_refund ?? 0),
            'restocking'  => (float) ($rr->restocking ?? 0),
            'extra'       => (float) ($rr->extra_adjustments ?? 0),
        ];

        $recommended = round(
            $breakdown['item_refund'] + $breakdown['ship_refund'] - $breakdown['restocking'] + $breakdown['extra'],
            2
        );

        $amount = (float) ($req->input('amount') ?: $recommended);

        // Xác định user_id khách
        $userId = $rr->user_id
            ?? optional($rr->order)->user_id
            ?? optional($rr->order?->user)->id;

        if (!$userId) {
            return back()->with('error', 'Không xác định được khách hàng của đơn hàng này.');
        }

        DB::transaction(function () use ($rr, $breakdown, $recommended, $amount, $req, $userId) {
            Refund::create([
                'return_request_id' => $rr->id,
                'order_id'          => $rr->order_id,
                'user_id'           => $userId,
                'amount'            => $amount,
                'breakdown'         => array_merge($breakdown, ['recommended' => $recommended]),
                'method'            => $req->input('method', 'bank'),
                'status'            => 'pending',
                'created_by'        => auth()->id(),
            ]);

            // RR sang trạng thái đang xử lý hoàn
            $rr->update(['status' => 'refund_processing']);
        });

        return back()->with('success', 'Đã tạo phiếu hoàn (pending).');
    }

    /** Đánh dấu phiếu hoàn DONE (đã chuyển khoản) */
    public function markDone(Refund $refund, Request $req)
    {
        $data = $req->validate([
            'bank_ref'       => ['required', 'string', 'max:190'],
            'transferred_at' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($refund, $data) {
            // 1) Cập nhật phiếu hoàn
            $refund->update([
                'status'         => 'done',
                'bank_ref'       => $data['bank_ref'],
                'transferred_at' => $data['transferred_at'] ?? now(),
                'updated_by'     => auth()->id(),
            ]);

            // 2) Cập nhật Return Request
            $rr = ReturnRequest::with(['items.actions'])
                ->lockForUpdate()
                ->findOrFail($refund->return_request_id);

            // Kiểm tra còn "Đổi" mà CHƯA có đơn đổi hay không
            $exQty = (int) $rr->items->sum(
                fn($it) => (int) ($it->actions?->where('action', 'exchange')->sum('quantity') ?? 0)
            );
            $hasExchangeOrder = !empty($rr->exchange_order_id);

            // Nếu còn phần ĐỔI chưa tạo đơn → để 'approved' để hiển thị nút "Tạo đơn đổi"
            $nextStatus = ($exQty > 0 && !$hasExchangeOrder) ? 'approved' : 'refunded';

            $rr->update([
                'status'      => $nextStatus,
                'refunded_at' => now(), // thời điểm hoàn tiền (vẫn có thể còn bước ĐỔI)
            ]);

            // 3) Cập nhật Order (theo schema hiện tại)
            $order = \App\Models\Order::lockForUpdate()->find($refund->order_id);
            if ($order) {
                // đánh dấu đã hoàn (tuỳ nghiệp vụ của bạn)
                $order->payment_status = 'refunded';
                $order->refunded_at = $order->refunded_at ?? now();
                $order->save();
            }
        });

        return back()->with('success', 'Đã đánh dấu phiếu hoàn: DONE.');
    }
}
