<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\ReturnRequest;
use App\Models\ReturnRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnRequestController extends Controller
{
    public function exchange($id)
    {
        $rr = ReturnRequest::with(['order', 'items.actions', 'items.orderItem'])->findOrFail($id);
        if ($rr->exchange_order_id) {
            return back()->with('error', 'Yêu cầu này đã tạo đơn đổi.');
        }

        DB::transaction(function () use ($rr) {
            // 1) Tạo đơn đổi
            $newOrder = Order::create([
                'user_id'   => $rr->order->user_id,
                // ... copy các field cần thiết (địa chỉ, phí ship, phương thức, v.v.)
                'status'    => 'pending',
                // GÁN CHIỀU NGƯỢC VỀ RR:
                'exchange_of_return_request_id' => $rr->id,
            ]);

            // 2) Thêm item theo tổng qty "exchange" của từng dòng RR
            foreach ($rr->items as $it) {
                $exQty = (int) ($it->actions->where('action', 'exchange')->sum('quantity') ?? 0);
                if ($exQty <= 0) continue;

                // Chọn variant đích (nếu action có chọn) – fallback SKU cũ
                $targetVariantId = optional(
                    $it->actions->where('action', 'exchange')->sortByDesc('id')->first()
                )->exchange_variant_id ?: $it->orderItem->product_variant_id;

                $price = $it->orderItem->price; // hoặc lấy từ variant đích
                OrderItem::create([
                    'order_id'           => $newOrder->id,
                    'product_id'         => $it->orderItem->product_id,
                    'product_variant_id' => $targetVariantId,
                    'product_name'       => $it->orderItem->product_name,
                    'price'              => $price,
                    'quantity'           => $exQty,
                    'total_price'        => $price * $exQty,
                ]);
            }

            // TODO: tính lại totals, ship, VAT ... nếu có method thì gọi:
            // $newOrder->recalculateTotals();

            // 3) Link chiều thuận (RR -> Order đổi) & cập nhật trạng thái RR
            $rr->update([
                'exchange_order_id' => $newOrder->id,
                'status'            => 'exchanged', // hoặc 'approved' tùy flow
            ]);
        });

        return back()->with('success', 'Đã tạo đơn đổi.');
    }
    // public function createExchange($rrId, Request $request)
    // {
    //     return DB::transaction(function () use ($rrId) {

    //         // KHÓA RR trước, tránh đua tay double click
    //         $rr = ReturnRequest::with([
    //             'order',
    //             'items.orderItem.productVariant',
    //             'items.actions' => fn($q) => $q->where('action', 'exchange'),
    //         ])->lockForUpdate()->findOrFail($rrId);

    //         // Re-check 1: nếu RR đã có link sang order đổi thì về luôn
    //         if (!empty($rr->exchange_order_id)) {
    //             return redirect()
    //                 ->route('admin.orders.show', $rr->exchange_order_id)
    //                 ->with('info', "Đơn đổi đã tồn tại #{$rr->exchange_order_id}.");
    //         }

    //         // Re-check 2: nếu đã có order có exchange_of_return_request_id = RR này thì về luôn
    //         if ($existingId = Order::where('exchange_of_return_request_id', $rr->id)->value('id')) {
    //             // Đồng bộ lại cột trên RR nếu bạn muốn
    //             if (empty($rr->exchange_order_id)) {
    //                 $rr->exchange_order_id = $existingId;
    //                 $rr->save();
    //             }
    //             return redirect()
    //                 ->route('admin.orders.show', $existingId)
    //                 ->with('info', "Đơn đổi đã tồn tại #{$existingId}.");
    //         }

    //         // Gom line hàng đổi từ các action "exchange"
    //         $lines = [];
    //         foreach ($rr->items as $it) {
    //             $qty = (int) $it->actions->sum('quantity');
    //             if ($qty <= 0) continue;

    //             $lastExchange    = $it->actions->last();
    //             $targetVariantId = $lastExchange?->exchange_variant_id ?: $it->orderItem?->product_variant_id;
    //             $targetVariant   = $targetVariantId ? ProductVariant::find($targetVariantId) : null;
    //             $price           = $targetVariant?->price ?? ($it->orderItem->price ?? 0);

    //             $lines[] = [
    //                 'product_id'         => $it->orderItem->product_id ?? null,
    //                 'product_variant_id' => $targetVariant?->id,
    //                 'product_name'       => $it->orderItem->product_name,
    //                 'sku'                => $targetVariant?->sku ?? ($it->orderItem->sku ?? null),
    //                 'price'              => $price,
    //                 'quantity'           => $qty,
    //                 'total_price'        => $price * $qty,
    //             ];
    //         }

    //         if (empty($lines)) {
    //             return back()->with('error', 'Không có dòng nào để tạo đơn đổi.');
    //         }

    //         $subtotal = array_sum(array_column($lines, 'total_price'));

    //         // Tạo order đổi (gán thuộc tính thủ công để chắc chắn được lưu)
    //         $order = new Order();
    //         $order->user_id                       = $rr->order->user_id ?? null;
    //         $order->order_code                    = 'EXC' . now()->format('ymdHis');
    //         $order->status                        = 'pending';
    //         $order->payment_status                = 'unpaid';
    //         $order->subtotal                      = $subtotal;
    //         $order->tax_amount                    = 0;
    //         $order->shipping_fee                  = 0;
    //         $order->total_amount                  = $subtotal;
    //         $order->exchange_of_return_request_id = $rr->id;   // <<< QUAN TRỌNG
    //         $order->save();

    //         $order->orderItems()->createMany($lines);

    //         // Ghi ngược lại vào RR
    //         $rr->exchange_order_id = $order->id;
    //         if ($rr->status === 'pending') {
    //             $rr->status = 'approved';
    //         }
    //         $rr->save();

    //         return redirect()
    //             ->route('admin.orders.show', $order->id)
    //             ->with('success', "Đã tạo đơn đổi #{$order->id}");
    //     });
    // }
    public function createExchange($rrId, Request $request)
    {
        return DB::transaction(function () use ($rrId) {

            // ✅ Khoá RR để tránh double-click / mở 2 tab
            $rr = ReturnRequest::with([
                'order',
                'items.orderItem.productVariant',
                'items.actions' => fn($q) => $q->where('action', 'exchange'),
            ])
                ->lockForUpdate()
                ->findOrFail($rrId);

            // ✅ Không cho tạo nếu request đã khoá
            if (!empty($rr->exchange_order_id) || in_array($rr->status, ['refunded', 'rejected'], true)) {
                return back()->with('error', 'Yêu cầu này đã khoá, không thể tạo đơn đổi.');
            }

            // ✅ Idempotent: đã có đơn nào trỏ vào RR này chưa?
            if ($existingId = Order::where('exchange_of_return_request_id', $rr->id)->value('id')) {
                return redirect()
                    ->route('admin.orders.show', $existingId)
                    ->with('info', "Đơn đổi đã tồn tại #{$existingId}.");
            }

            // ==== build $lines y hệt bạn đang làm ====
            $lines = [];
            foreach ($rr->items as $it) {
                $qty = (int) $it->actions->sum('quantity'); // chỉ lấy qty action 'exchange'
                if ($qty <= 0) continue;

                $lastExchange    = $it->actions->last();
                $targetVariantId = $lastExchange?->exchange_variant_id ?: $it->orderItem?->product_variant_id;
                $targetVariant   = $targetVariantId ? ProductVariant::find($targetVariantId) : null;
                $price           = $targetVariant?->price ?? ($it->orderItem->price ?? 0);

                $lines[] = [
                    'product_id'         => $it->orderItem->product_id ?? null,
                    'product_variant_id' => $targetVariant?->id,
                    'product_name'       => $it->orderItem->product_name,
                    'sku'                => $targetVariant?->sku ?? ($it->orderItem->sku ?? null),
                    'price'              => $price,
                    'quantity'           => $qty,
                    'total_price'        => $price * $qty,
                ];
            }
            if (empty($lines)) return back()->with('error', 'Không có dòng nào để tạo đơn đổi.');

            $subtotal = array_sum(array_column($lines, 'total_price'));

            // ✅ Tạo đơn: đảm bảo RR id được gắn
            $order = Order::create([
                'user_id'                       => $rr->order->user_id ?? null,
                'exchange_of_return_request_id' => $rr->id,   // <— BẮT BUỘC
                'order_code'                    => 'EXC' . now()->format('ymdHis'),
                'status'                        => 'padding',
                'payment_status'                => 'unpaid',
                'subtotal'                      => $subtotal,
                'tax_amount'                    => 0,
                'shipping_fee'                  => 0,
                'total_amount'                  => $subtotal,
            ]);
            $order->orderItems()->createMany($lines);

            // ✅ Ghi ngược lại RR & khoá luôn
            $rr->exchange_order_id = $order->id;
            if ($rr->status === 'pending') $rr->status = 'approved';
            $rr->save();

            return redirect()
                ->route('admin.orders.show', $order->id)
                ->with('success', "Đã tạo đơn đổi #{$order->id}");
        });
    }
    public function reject($id, Request $request)
    {
        $rr = ReturnRequest::findOrFail($id);
        if ($rr->status !== 'pending') {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }
        $request->validate(['reason' => 'required|string|min:3']);

        $rr->status     = 'rejected';
        $rr->admin_note = $request->input('reason');
        $rr->handled_by = auth()->id();
        $rr->handled_at = now();
        $rr->save();

        return back()->with('success', '❌ Đã từ chối yêu cầu.');
    }

    /**
     * Hoàn tiền theo qty_refund + unit_price_paid (prorate nếu thiếu).
     * Đặt trạng thái request sang 'refunded'.
     */
    public function refund($id, Request $request)
    {
        $rr = ReturnRequest::with('items.orderItem')->findOrFail($id);
        if (!in_array($rr->status, ['pending', 'approved'])) {
            return back()->with('error', 'Yêu cầu này không thể hoàn tiền.');
        }

        $totalRefund = 0.0;

        DB::transaction(function () use ($rr, $request, &$totalRefund) {
            foreach ($rr->items as $it) {
                if ((int)$it->qty_refund <= 0) continue;

                $unitPaid = $it->unit_price_paid ?? ($it->orderItem->total_price / max(1, $it->orderItem->quantity));
                $amount   = $it->refund_amount > 0 ? $it->refund_amount : round($unitPaid * $it->qty_refund, 2);

                $totalRefund += $amount;

                $it->refund_amount = $amount;
                $it->item_status   = 'refunded';
                $it->save();
            }

            $rr->total_refund_amount = ($rr->total_refund_amount ?? 0) + $totalRefund;
            $rr->status     = 'refunded';
            $rr->admin_note = trim(($rr->admin_note ? $rr->admin_note . "\n" : '') . 'Refunded: ' . number_format($totalRefund, 2));
            $rr->handled_by = auth()->id();
            $rr->handled_at = now();
            $rr->save();

            // TODO: Gọi cổng thanh toán / ghi Store Credit tại đây nếu bạn dùng MoMo/VNPay/Wallet
        });

        return back()->with('success', '💸 Đã hoàn tiền tổng: ' . number_format($totalRefund, 2));
    }

    /**
     * (Tuỳ chọn) Approve cấp request: đọc các item hiện có, nếu có bất kỳ qty_exchange/qty_refund > 0 thì set 'approved',
     * nếu tất cả item đều không có hành động (chỉ reject) thì set 'rejected'.
     */
    public function approve($id, Request $request)
    {
        $rr = ReturnRequest::with('items')->findOrFail($id);
        if ($rr->status !== 'pending') {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }

        $hasAction = $rr->items()->whereRaw('(qty_exchange + qty_refund) > 0')->exists();
        $rr->status     = $hasAction ? 'approved' : 'rejected';
        $rr->admin_note = $request->input('note', $hasAction ? 'Đã duyệt yêu cầu' : 'Không có sản phẩm nào được duyệt');
        $rr->handled_by = auth()->id();
        $rr->handled_at = now();
        $rr->save();

        return back()->with('success', '✅ Đã cập nhật trạng thái yêu cầu.');
    }
    public function finalizeReject($id)
    {
        $rr = ReturnRequest::with('items.actions', 'order')->findOrFail($id);

        DB::transaction(function () use ($rr) {
            $hasRefundOrExchange = false;
            $hasReject = false;

            foreach ($rr->items as $it) {
                foreach ($it->actions as $ac) {
                    if (in_array($ac->action, ['refund', 'exchange'])) {
                        $hasRefundOrExchange = true;
                    } elseif ($ac->action === 'reject') {
                        $hasReject = true;
                    }
                }
            }

            if ($hasRefundOrExchange) {
                // 👉 Có sản phẩm được xử lý hoàn/đổi → giữ trạng thái processing
                // Chỉ ghi chú để dễ theo dõi
                $rr->admin_note = trim(($rr->admin_note ? $rr->admin_note . "\n" : '') . 'Đã xác nhận từ chối 1 phần.');
            } elseif ($hasReject) {
                // 👉 Tất cả đều bị từ chối → chốt rejected
                $rr->status = 'rejected';

                if ($rr->order && $rr->order->status === 'return_requested') {
                    $rr->order->status = 'completed';
                    $rr->order->save();
                }
            }

            $rr->save();
        });

        return back()->with('success', 'Đã chốt từ chối yêu cầu.');
    }
}
