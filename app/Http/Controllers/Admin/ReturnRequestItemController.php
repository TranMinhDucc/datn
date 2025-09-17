<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\ReturnRequest;
use App\Models\ReturnRequestItem;
use App\Models\ReturnRequestItemAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnRequestItemController extends Controller
{
    /**
     * Cập nhật 1 item trong yêu cầu RMA theo 4 hành động:
     * - split: chia qty thành exchange/refund/reject ngay trên CHÍNH DÒNG (không clone)
     * - approve: duyệt đổi 100% số lượng của dòng
     * - refund: hoàn 100% số lượng của dòng (prorate nếu không nhập số tiền)
     * - reject: từ chối một phần hoặc toàn bộ
     */
    public function update(Request $request, $id)
    {
        $item   = ReturnRequestItem::with(['returnRequest', 'orderItem.productVariant', 'orderItem.product'])->findOrFail($id);
        $rr     = $item->returnRequest;
        $oi     = $item->orderItem;
        $action = $request->input('action');

        $originalQty = (int) ($item->quantity ?? $oi->quantity);
        $unitPaid    = $item->unit_price_paid ?? (float) ($oi->total_price / max(1, $oi->quantity));

        return DB::transaction(function () use ($request, $item, $rr, $oi, $action, $originalQty, $unitPaid) {
            // Tổng đã xử lý ở các yêu cầu khác (chống vượt SL đã giao)
            $approvedAcrossAll = ReturnRequestItem::where('order_item_id', $oi->id)
                ->where('id', '!=', $item->id)
                ->sum(DB::raw('qty_exchange + qty_refund + qty_reject'));

            $deliveredQty = (int) ($oi->quantity_delivered ?? $oi->quantity);

            if ($action === 'split') {
                // ---- 1) lấy mảng dòng đổi ----
                $exchanges = $request->input('exchanges', []);
                // nếu form cũ (1 dòng), chuyển thành mảng
                if (empty($exchanges) && $request->filled('exchange_qty')) {
                    $exQty   = (int) $request->integer('exchange_qty', 0);
                    $exVarId = $request->input('exchange_variant_id');
                    if ($exQty > 0) {
                        $exchanges = [['variant_id' => $exVarId, 'qty' => $exQty]];
                    }
                }

                $refundQty     = (int) $request->integer('refund_qty', 0);
                $rejectQty     = (int) $request->integer('reject_qty', 0);
                $refundInput   = (float) $request->input('refund_amount', 0.0);

                $sumExchange = collect($exchanges)->sum(fn($r) => (int) ($r['qty'] ?? 0));
                $total = $sumExchange + $refundQty + $rejectQty;

                if ($total <= 0) return back()->with('error', 'Bạn chưa nhập số lượng xử lý.');
                if ($total > $originalQty) {
                    return back()->with('error', "Tổng số lượng ($total) vượt quá số lượng gốc ($originalQty).");
                }
                if (($approvedAcrossAll + $total) > $deliveredQty) {
                    return back()->with('error', 'Vượt quá số lượng đã giao của sản phẩm.');
                }

                // ---- 2) ghi action exchange (nhiều dòng) ----
                $sumRefundAmount = 0.0;

                foreach ($exchanges as $row) {
                    $qty = (int) ($row['qty'] ?? 0);
                    if ($qty <= 0) continue;

                    ReturnRequestItemAction::create([
                        'return_request_item_id' => $item->id,
                        'action'                 => 'exchange',
                        'exchange_variant_id'    => $row['variant_id'] ?? null, // null = giữ SKU cũ
                        'quantity'               => $qty,
                    ]);
                }

                // ---- 3) ghi action refund (1 dòng) ----
                if ($refundQty > 0) {
                    $refundAmount = $refundInput > 0 ? $refundInput : round($unitPaid * $refundQty, 2);
                    $sumRefundAmount += $refundAmount;

                    ReturnRequestItemAction::create([
                        'return_request_item_id' => $item->id,
                        'action'                 => 'refund',
                        'quantity'               => $refundQty,
                        'refund_amount'          => $refundAmount,
                        'note'                   => $request->input('reason'),
                    ]);
                }

                // ---- 4) ghi action reject (1 dòng) ----
                if ($rejectQty > 0) {
                    ReturnRequestItemAction::create([
                        'return_request_item_id' => $item->id,
                        'action'                 => 'reject',
                        'quantity'               => $rejectQty,
                        'note'                   => $request->input('reject_reason'),
                    ]);
                }

                // ---- 5) cập nhật tổng trên bảng cha ----
                $exTotal = $item->actions()->where('action', 'exchange')->sum('quantity');
                $rfRow   = $item->actions()->where('action', 'refund');
                $rfTotal = (int) $rfRow->sum('quantity');
                $rfAmt   = (float) $rfRow->sum('refund_amount');
                $rjTotal = (int) $item->actions()->where('action', 'reject')->sum('quantity');

                $item->qty_exchange  = $exTotal;
                $item->qty_refund    = $rfTotal;
                $item->qty_reject    = $rjTotal;
                $item->refund_amount = $rfAmt;
                $item->item_status   = $exTotal && !$rfTotal && !$rjTotal ? 'approved_exchange'
                    : (!$exTotal && $rfTotal && !$rjTotal ? 'approved_refund'
                        : ($exTotal || $rfTotal ? 'approved_mixed' : 'rejected'));
                $item->save();

                if ($rfAmt > 0) {
                    $rr->total_refund_amount = ($rr->total_refund_amount ?? 0) + $rfAmt;
                }
                if ($rr->status === 'pending' && ($exTotal > 0 || $rfTotal > 0)) {
                    $rr->status = 'approved';
                }
                // nếu tất cả item của request không có ex/rf ⇒ rejected
                $noActionCount = $rr->items()->whereRaw('(qty_exchange + qty_refund) = 0')->count();
                if ($noActionCount === $rr->items()->count()) $rr->status = 'rejected';
                $rr->save();

                return back()->with('success', 'Đã ghi chi tiết xử lý.');
            }

            // Các nút nhanh approve/refund/reject (100%) → ghi 1 action tương ứng
            if ($action === 'approve') {
                if (($approvedAcrossAll + $originalQty) > $deliveredQty) {
                    return back()->with('error', 'Vượt quá số lượng đã giao của sản phẩm.');
                }
                ReturnRequestItemAction::create([
                    'return_request_item_id' => $item->id,
                    'action'                 => 'exchange',
                    'exchange_variant_id'    => $item->exchange_variant_id, // nếu có chọn sẵn
                    'quantity'               => $originalQty,
                ]);
                $item->qty_exchange = $originalQty;
                $item->item_status  = 'approved_exchange';
                $item->save();
                if ($rr->status === 'pending') $rr->status = 'approved';
                $rr->save();

                return back()->with('success', 'Đã duyệt đổi.');
            }

            if ($action === 'refund') {
                $rfAmount = (float) $request->input('refund_amount', 0);
                if ($rfAmount <= 0) $rfAmount = round($unitPaid * $originalQty, 2);

                ReturnRequestItemAction::create([
                    'return_request_item_id' => $item->id,
                    'action'                 => 'refund',
                    'quantity'               => $originalQty,
                    'refund_amount'          => $rfAmount,
                    'note'                   => $request->input('reason'),
                ]);

                $item->qty_refund    = $originalQty;
                $item->refund_amount = $rfAmount;
                $item->item_status   = 'approved_refund';
                $item->save();

                $rr->total_refund_amount = ($rr->total_refund_amount ?? 0) + $rfAmount;
                if ($rr->status === 'pending') $rr->status = 'approved';
                $rr->save();

                return back()->with('success', 'Đã ghi hoàn tiền.');
            }

            if ($action === 'reject') {
                ReturnRequestItemAction::create([
                    'return_request_item_id' => $item->id,
                    'action'                 => 'reject',
                    'quantity'               => $originalQty,
                    'note'                   => $request->input('reject_reason') ?? $request->input('reason'),
                ]);

                $item->qty_reject  = $originalQty;
                $item->item_status = 'rejected';
                $item->save();

                // Nếu toàn bộ item của request đều rejected
                if ($rr->items()->where('item_status', '!=', 'rejected')->count() === 0) {
                    $rr->status = 'rejected';
                    $rr->save();
                }

                return back()->with('success', 'Đã từ chối.');
            }

            return back()->with('error', 'Hành động không hợp lệ.');
        });
    }

    /**
     * Tạo đơn đổi cho một ReturnRequest:
     * - Lấy các item có qty_exchange > 0.
     * - Tạo order mới, tính chênh lệch giá (giá hiện tại - giá đã trả), ghi vào admin_note.
     * - Không thay đổi status cấp request ngoài 'approved'.
     */
    public function handleExchange($id)
    {
        return DB::transaction(function () use ($id) {
            // 1) Khóa RR để tránh tạo trùng khi double-click
            $rr = ReturnRequest::with([
                'order',
                'items.orderItem.productVariant',
                'items.orderItem.product',
                'items.actions' => fn($q) => $q->where('action', 'exchange'),
            ])
                ->lockForUpdate()
                ->findOrFail($id);

            // 2) Không cho tạo nếu trạng thái không hợp lệ
            if (!in_array($rr->status, ['pending', 'approved'], true)) {
                return back()->with('error', 'Yêu cầu không ở trạng thái có thể tạo đơn đổi.');
            }

            // 3) Nếu RR đã link tới order đổi -> quay lại ngay
            if (!empty($rr->exchange_order_id)) {
                return redirect()
                    ->route('admin.orders.show', $rr->exchange_order_id)
                    ->with('info', "Đơn đổi đã tồn tại #{$rr->exchange_order_id}.");
            }

            // 4) Nếu bảng orders đã có đơn gắn RR này -> backfill & quay lại
            if ($existingId = Order::where('exchange_of_return_request_id', $rr->id)->value('id')) {
                if (empty($rr->exchange_order_id)) {
                    $rr->exchange_order_id = $existingId;
                    $rr->save();
                }
                return redirect()
                    ->route('admin.orders.show', $existingId)
                    ->with('info', "Đơn đổi đã tồn tại #{$existingId}.");
            }

            // 5) Gom các action EXCHANGE
            $exActions = $rr->items->flatMap(fn($it) => $it->actions)->where('action', 'exchange')->values();
            if ($exActions->isEmpty()) {
                return back()->with('error', 'Chưa có dòng đổi nào.');
            }

            // 6) Khóa luôn order gốc (phòng khi đồng thời có thao tác khác)
            $original = $rr->order()->lockForUpdate()->first();

            // 7) Tạo order mới
            $newOrder = $original->replicate([
                'subtotal',
                'discount_amount',
                'shipping_fee',
                'tax_amount',
                'total_amount',
                'shipping_tracking_code',
                'ghn_order_code',
                'momo_trans_id',
                'momo_order_id',
                'paid_at',
                'delivered_at',
                'completed_at',
                'refunded_at',
            ]);
            $newOrder->order_code  = 'EXC' . now()->format('ymdHis');
            $newOrder->status      = 'pending';
            $newOrder->is_exchange = 1;
            $newOrder->payment_status = $original->payment_status === 'paid' ? 'paid' : 'unpaid';
            $newOrder->is_paid        = $original->payment_status === 'paid' ? 1 : 0;

            // reset tiền
            $newOrder->subtotal = 0;
            $newOrder->discount_amount = 0;
            $newOrder->shipping_fee = 0;
            $newOrder->tax_amount = 0;
            $newOrder->total_amount = 0;

            // **QUAN TRỌNG**: gắn RR id để đảm bảo idempotent
            $newOrder->exchange_of_return_request_id = $rr->id;
            $newOrder->save();

            // 8) Dòng hàng
            $subtotalNew = 0;
            $subtotalOldEquiv = 0;

            foreach ($exActions as $act) {
                /** @var \App\Models\ReturnRequestItem $rrItem */
                $rrItem = $act->item;
                $oi     = $rrItem->orderItem;

                $variantId = $act->exchange_variant_id ?: ($oi->product_variant_id ?? null);
                $variant   = $variantId ? \App\Models\ProductVariant::find($variantId) : null;

                $unitNew = $variant?->price ?? $oi->price;
                $sku     = $variant?->sku ?? $oi->sku ?? optional($oi->product)->sku ?? 'EX-ITEM';

                $new = new \App\Models\OrderItem();
                $new->order_id           = $newOrder->id;
                $new->product_id         = $oi->product_id;
                $new->product_variant_id = $variant?->id;
                $new->product_name       = $oi->product_name;
                $new->sku                = $sku;
                $new->image_url          = $variant?->image_url ?? $oi->image_url;
                $new->variant_values     = $variant ? json_encode($variant->only(['size', 'color'])) : $oi->variant_values;
                $new->price              = $unitNew;
                $new->quantity           = (int) $act->quantity;
                $new->total_price        = $new->price * $new->quantity;
                $new->save();

                $subtotalNew      += $new->total_price;
                $unitPaidOld       = $rrItem->unit_price_paid ?? ($oi->total_price / max(1, $oi->quantity));
                $subtotalOldEquiv += $unitPaidOld * $new->quantity;
            }

            // 9) Cập nhật tổng tiền đơn đổi
            $newOrder->subtotal     = $subtotalNew;
            $newOrder->total_amount = $subtotalNew; // ship/tax xử lý sau
            $newOrder->save();

            // 10) Ghi ngược vào RR + ghi chú chênh lệch
            $diff = round($subtotalNew - $subtotalOldEquiv, 2);
            $note = "Exchange order #{$newOrder->order_code}. Price diff: " . ($diff >= 0 ? '+' : '-') . number_format(abs($diff), 2);

            $rr->admin_note        = trim(($rr->admin_note ? $rr->admin_note . "\n" : '') . $note);
            $rr->exchange_order_id = $newOrder->id;
            if ($rr->status === 'pending') $rr->status = 'approved';
            $rr->handled_by = auth()->id();
            $rr->handled_at = now();
            if ($diff < 0) {
                $rr->total_refund_amount = ($rr->total_refund_amount ?? 0) + abs($diff);
            }
            $rr->save();

            return redirect()
                ->route('admin.orders.show', $newOrder->id)
                ->with('success', 'Đã tạo đơn đổi thành công.');
        });
    }


    public function setVariant(Request $request, $id)
    {
        $request->validate(['variant_id' => 'required|integer|exists:product_variants,id']);

        $item = ReturnRequestItem::with('orderItem.product')->findOrFail($id);
        $variant = \App\Models\ProductVariant::findOrFail($request->integer('variant_id'));

        // đảm bảo chọn đúng SP
        if ($variant->product_id !== $item->orderItem->product_id) {
            return back()->with('error', 'Variant không thuộc sản phẩm của dòng này.');
        }

        // (khuyến nghị) kiểm tra tồn kho cho SL cần đổi
        $need = (int) max(1, $item->qty_exchange ?: $item->quantity);
        if (isset($variant->stock) && $variant->stock < $need) {
            return back()->with('error', 'Tồn kho variant không đủ cho số lượng đổi.');
        }

        $item->exchange_variant_id = $variant->id;
        $item->save();

        return back()->with('success', 'Đã chọn size/màu để đổi.');
    }
}
