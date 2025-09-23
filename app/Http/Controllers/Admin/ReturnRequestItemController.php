<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\OrderAdjustment;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Refund;
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
            // 1) Lock RR để tránh double-click
            $rr = ReturnRequest::with([
                'order',
                'items.orderItem.productVariant',
                'items.orderItem.product',
                'items.actions' => fn($q) => $q->where('action', 'exchange'),
            ])->lockForUpdate()->findOrFail($id);

            // 2) Trạng thái RR phải hợp lệ
            if (!in_array($rr->status, [
                'pending',
                'approved',
                'exchange_in_progress',
                'exchange_and_refund_processing',
                'refund_processing',     // ✅ thêm vào
                'rejected_temp',         // ✅ nếu muốn cho phép sau QC fail 1 phần
            ], true)) {
                return back()->with('error', 'Yêu cầu không ở trạng thái có thể tạo đơn đổi.');
            }


            // 3) Nếu đã có link tới đơn đổi thì quay lại ngay
            if (!empty($rr->exchange_order_id)) {
                return redirect()
                    ->route('admin.orders.show', $rr->exchange_order_id)
                    ->with('info', "Đơn đổi đã tồn tại #{$rr->exchange_order_id}.");
            }

            // 4) Nếu trong bảng orders đã có đơn gắn RR này thì backfill & quay lại
            if ($existingId = \App\Models\Order::where('exchange_of_return_request_id', $rr->id)->value('id')) {
                if (empty($rr->exchange_order_id)) {
                    $rr->exchange_order_id = $existingId;
                    $rr->save();
                }
                return redirect()->route('admin.orders.show', $existingId)
                    ->with('info', "Đơn đổi đã tồn tại #{$existingId}.");
            }

            // 5) Gom các action ĐỔI (chỉ lấy QC passed)
            $exActions = $rr->items
                ->flatMap(fn($it) => $it->actions)
                ->where('action', 'exchange')
                ->filter(fn($act) => str_starts_with($act->qc_status, 'passed'))
                ->values();

            if ($exActions->isEmpty()) {
                return back()->with('error', 'Chưa có dòng đổi nào QC đạt.');
            }


            // 6) Lock đơn gốc
            $original = $rr->order()->lockForUpdate()->first();
            if ($original->status === 'cancelled') {
                return back()->with('error', 'Đơn gốc đã huỷ, không thể tạo đơn đổi.');
            }

            // 7) Tạo đơn đổi
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
            $newOrder->payment_status = ($original->payment_status === 'paid') ? 'paid' : 'unpaid';
            $newOrder->is_paid        = ($original->payment_status === 'paid') ? 1 : 0;

            // reset tiền
            $newOrder->subtotal = 0;
            $newOrder->discount_amount = 0;
            $newOrder->shipping_fee = 0;
            $newOrder->tax_amount = 0;
            $newOrder->total_amount = 0;

            $newOrder->exchange_of_return_request_id = $rr->id;
            $newOrder->save();

            // 8) Thêm items
            $subtotalNew = 0;
            $subtotalOldEquiv = 0;

            foreach ($exActions as $act) {
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
                if ($variant) {
                    InventoryTransaction::create([
                        'product_id'         => $variant->product_id,
                        'product_variant_id' => $variant->id,
                        'type'               => 'export',
                        'quantity'           => $new->quantity,
                        'note'               => "Xuất kho cho đơn đổi #{$newOrder->order_code}",
                        'created_by'         => auth()->id(),
                    ]);

                    $variant->decrement('quantity', $new->quantity);
                }

                $subtotalNew      += $new->total_price;
                $unitPaidOld       = $rrItem->unit_price_paid ?? ($oi->total_price / max(1, $oi->quantity));
                $subtotalOldEquiv += $unitPaidOld * $new->quantity;
            }

            // 9) Tổng & credit đổi hàng
            $credit = round($subtotalOldEquiv, 2);
            $diff   = round($subtotalNew - $credit, 2);

            $newOrder->subtotal = $subtotalNew;
            $newOrder->shipping_fee = 0;
            $newOrder->tax_amount   = 0;
            $newOrder->total_amount = $subtotalNew - $credit;

            if ($diff > 0) {
                $newOrder->payment_status = 'unpaid';
                $newOrder->is_paid = 0;
            } else {
                $newOrder->payment_status = 'paid';
                $newOrder->is_paid = 1;
            }
            $newOrder->save();

            $rr->status = 'exchange_in_progress';
            $rr->exchange_order_id = $newOrder->id;
            $rr->save();
            $user = $rr->order->user;
            $user->notify(new \App\Notifications\ExchangeOrderCreatedNotification($newOrder));

            // 9.1) Credit
            OrderAdjustment::create([
                'order_id' => $newOrder->id,
                'label'    => 'Tín dụng đổi hàng từ ' . $original->order_code,
                'code'     => 'EXCHANGE_CREDIT',
                'type'     => 'discount',
                'amount'   => $credit,
                'visible_to_customer' => true,       // hiển thị cho khách
                'category' => 'exchange_credit',     // phân loại (vd: exchange_credit, price_diff, shipping_fee,…)
                'created_by' => auth()->id(),        // nếu muốn log lại admin thực hiện
            ]);


            // (nếu diff < 0 => tạo phiếu hoàn tiền chênh lệch)
            if ($diff < 0) {
                Refund::create([
                    'order_id'          => $newOrder->id,
                    'return_request_id' => $rr->id,
                    'user_id'           => $original->user_id,
                    'amount'            => abs($diff),
                    'status'            => 'pending',
                    'note'              => 'Hoàn chênh khi đổi hàng ' . $newOrder->order_code,
                    'currency'          => 'VND',
                    'method'            => 'bank',
                    'created_by'        => auth()->id(),
                    'processed_by'      => null,
                ]);
            }

            // 10) Update trạng thái đơn gốc
            $hasRefund = \App\Models\Refund::where('order_id', $original->id)
                ->whereIn('status', ['pending', 'done'])
                ->exists();

            if ($hasRefund) {
                $original->status = 'exchange_and_refund_processing';
            } else {
                $original->status = 'exchange_in_progress';
            }
            $original->save();

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

    public function qc(Request $request, $id)
    {
        $item = ReturnRequestItem::with(['orderItem.productVariant'])->findOrFail($id);
        $status = $request->input('qc_status'); // passed | failed
        $note   = $request->input('qc_note');

        if (!in_array($status, ['passed', 'failed'])) {
            return back()->with('error', 'Trạng thái QC không hợp lệ.');
        }

        return DB::transaction(function () use ($item, $status, $note) {
            $item->qc_status = $status;
            $item->qc_note   = $note;
            $item->save();
            $user = $item->returnRequest->order->user;
            $user->notify(new \App\Notifications\QcCompletedNotification($item->returnRequest));

            $oi = $item->orderItem;

            if ($status === 'passed') {
            } else {
                // Hàng hỏng → không nhập kho, chỉ ghi log
                InventoryTransaction::create([
                    'product_id'         => $oi->product_id,
                    'product_variant_id' => $oi->product_variant_id,
                    'type'               => 'discard',
                    'quantity'           => $item->qty_refund,
                    'note'               => "QC Failed - loại bỏ hàng từ RR #{$item->return_request_id}",
                    'created_by'         => auth()->id(),
                ]);
            }

            return back()->with('success', 'Đã cập nhật kết quả QC.');
        });
    }
}
