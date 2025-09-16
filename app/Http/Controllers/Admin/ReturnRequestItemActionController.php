<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use App\Models\ReturnRequestItem;
use App\Models\ReturnRequestItemAction;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReturnRequestItemActionController extends Controller
{
    /**
     * Thêm 1 action (exchange/refund/reject) cho ReturnRequestItem.
     * POST /admin/return-requests/items/{item}/actions
     */
    public function store(Request $request, $itemId)
    {
        $item = ReturnRequestItem::with(['orderItem', 'returnRequest', 'actions'])->findOrFail($itemId);
        $rr   = $item->returnRequest;
        $oi   = $item->orderItem;

        // KHÓA khi không còn được phép chỉnh
        if ($resp = $this->ensureEditable($rr)) return $resp;

        $data = $request->validate([
            'action'              => ['required', Rule::in(['exchange', 'refund', 'reject'])],
            'quantity'            => ['required', 'integer', 'min:1'],
            // nếu muốn allow giữ SKU cũ khi exchange: bỏ required_if
            'exchange_variant_id' => ['nullable', 'integer', 'exists:product_variants,id', 'required_if:action,exchange'],
            'refund_amount'       => ['nullable', 'numeric', 'min:0'],
            'note'                => ['nullable', 'string', 'max:2000'],
        ]);

        $qtyNew       = (int) $data['quantity'];
        $deliveredQty = (int) ($oi->quantity_delivered ?? $oi->quantity);
        $originalQty  = (int) ($item->quantity ?? $oi->quantity);

        // Tổng đã tạo action cho CHÍNH item này
        $sumThisItem = (int) $item->actions()->sum('quantity');

        // Tổng action đã dùng cho CÙNG order_item ở các request item khác
        $sumOthersSameOI = (int) ReturnRequestItemAction::whereHas('item', function ($q) use ($item, $oi) {
            $q->where('order_item_id', $oi->id)->where('id', '!=', $item->id);
        })->sum('quantity');

        // Validate số lượng
        if ($sumThisItem + $qtyNew > $originalQty) {
            return back()->with('error', "Vượt số lượng đã yêu cầu trên dòng (đã xử lý {$sumThisItem}/{$originalQty}).");
        }
        if ($sumOthersSameOI + $sumThisItem + $qtyNew > $deliveredQty) {
            return back()->with('error', 'Vượt quá số lượng đã giao của sản phẩm.');
        }

        // Lấy variant đích với exchange (đã validate exists)
        $exchangeVariantId = $data['action'] === 'exchange' ? ($data['exchange_variant_id'] ?? null) : null;

        // Tính refund_amount (nếu không nhập -> prorate)
        $refundAmount = null;
        if ($data['action'] === 'refund') {
            $refundAmount = $data['refund_amount'] ?? null;
            if ($refundAmount === null) {
                $unitPaid     = $item->unit_price_paid ?? ($oi->total_price / max(1, $oi->quantity));
                $refundAmount = round($unitPaid * $qtyNew, 2);
            }
        }

        DB::transaction(function () use ($item, $rr, $data, $qtyNew, $exchangeVariantId, $refundAmount) {
            // 1) Tạo action — dùng cột 'action'
            $action = new ReturnRequestItemAction();
            $action->return_request_item_id = $item->id;
            $action->action                 = $data['action']; // exchange|refund|reject
            $action->quantity               = $qtyNew;
            $action->exchange_variant_id    = $exchangeVariantId;
            $action->refund_amount          = $refundAmount;
            $action->note                   = $data['note'] ?? null;
            $action->created_by             = auth()->id();
            $action->updated_by             = null;
            $action->save();

            // 2) Cộng gộp lại
            $this->recomputeItem($item->fresh(['actions']));
            $this->recomputeRequest($rr->fresh(['items.actions']));
        });

        return back()->with('success', 'Đã thêm hành động xử lý.');
    }


    /**
     * Cập nhật 1 action.
     * PUT /admin/return-requests/items/actions/{action}
     */
    public function update(Request $request, $actionId)
    {
        $action = ReturnRequestItemAction::with(['item.orderItem', 'item.returnRequest', 'item.actions'])->findOrFail($actionId);
        $item   = $action->item;
        $rr     = $item->returnRequest;
        $oi     = $item->orderItem;

        // KHÓA khi không còn được phép chỉnh
        if ($resp = $this->ensureEditable($rr)) return $resp;

        $data = $request->validate([
            'action'              => ['required', Rule::in(['exchange', 'refund', 'reject'])],
            'quantity'            => ['required', 'integer', 'min:1'],
            'exchange_variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'refund_amount'       => ['nullable', 'numeric', 'min:0'],
            'note'                => ['nullable', 'string', 'max:2000'],
        ]);

        $qtyNew       = (int) $data['quantity'];
        $deliveredQty = (int) ($oi->quantity_delivered ?? $oi->quantity);
        $originalQty  = (int) ($item->quantity ?? $oi->quantity);

        // Tổng của CHÍNH item này (trừ action hiện tại)
        $sumThisItemOthers = (int) $item->actions()->where('id', '!=', $action->id)->sum('quantity');

        // Tổng đã dùng cho CÙNG order_item ở item KHÁC
        $sumOthersSameOI = (int) ReturnRequestItemAction::whereHas('item', function ($q) use ($item, $oi) {
            $q->where('order_item_id', $oi->id)->where('id', '!=', $item->id);
        })->sum('quantity');

        if ($sumThisItemOthers + $qtyNew > $originalQty) {
            return back()->with('error', "Vượt số lượng đã yêu cầu trên dòng (đã xử lý {$sumThisItemOthers}/{$originalQty} ngoại trừ action hiện tại).");
        }
        if ($sumOthersSameOI + $sumThisItemOthers + $qtyNew > $deliveredQty) {
            return back()->with('error', 'Vượt quá số lượng đã giao của sản phẩm.');
        }

        $exchangeVariantId = $data['action'] === 'exchange' ? ($data['exchange_variant_id'] ?? null) : null;

        $refundAmount = null;
        if ($data['action'] === 'refund') {
            $refundAmount = $data['refund_amount'] ?? null;
            if ($refundAmount === null) {
                $unitPaid     = $item->unit_price_paid ?? ($oi->total_price / max(1, $oi->quantity));
                $refundAmount = round($unitPaid * $qtyNew, 2);
            }
        }

        DB::transaction(function () use ($action, $item, $rr, $data, $qtyNew, $exchangeVariantId, $refundAmount) {
            // 1) Update — dùng cột 'action'
            $action->action              = $data['action'];
            $action->quantity            = $qtyNew;
            $action->exchange_variant_id = $exchangeVariantId;
            $action->refund_amount       = $refundAmount;
            $action->note                = $data['note'] ?? null;
            $action->updated_by          = auth()->id();
            $action->save();

            // 2) Recompute
            $this->recomputeItem($item->fresh(['actions']));
            $this->recomputeRequest($rr->fresh(['items.actions']));
        });

        return back()->with('success', 'Đã cập nhật hành động.');
    }


    /**
     * Xoá 1 action.
     * DELETE /admin/return-requests/items/actions/{action}
     */
    public function destroy($actionId)
    {
        // Lấy action + item + RR
        $action = ReturnRequestItemAction::with(['item.returnRequest', 'item.actions'])->findOrFail($actionId);
        $item   = $action->item;
        $rr     = $item->returnRequest;

        // Chặn thao tác khi RR đã tạo đơn đổi hoặc đã kết thúc
        if (!empty($rr->exchange_order_id) || in_array($rr->status, ['refunded', 'rejected'], true)) {
            return back()->with('error', 'Yêu cầu này đã có đơn đổi/đã kết thúc, không thể xoá dòng xử lý.');
        }

        // Re-check trong transaction + lock để chống double click
        return DB::transaction(function () use ($action) {
            // Khoá RR để tránh thay đổi song song rồi mới xoá
            $rrLocked = ReturnRequest::lockForUpdate()->find($action->item->return_request_id);
            if (!empty($rrLocked->exchange_order_id) || in_array($rrLocked->status, ['refunded', 'rejected'], true)) {
                return back()->with('error', 'Yêu cầu này đã có đơn đổi/đã kết thúc, không thể xoá dòng xử lý.');
            }

            $item = $action->item; // cần lại để recompute sau khi delete
            $action->delete();

            // Tính lại tổng cho item + request
            $this->recomputeItem($item->fresh(['actions']));
            $this->recomputeRequest($rrLocked->fresh(['items.actions']));

            return back()->with('success', 'Đã xoá hành động.');
        });
    }


    /* ===================== Helpers ===================== */

    /**
     * Cộng gộp lại các action -> đổ vào cột tổng trên item + item_status.
     */
    private function recomputeItem(ReturnRequestItem $item): void
    {
        $ex = (int) $item->actions->where('action', 'exchange')->sum('quantity');
        $rf = (int) $item->actions->where('action', 'refund')->sum('quantity');
        $rj = (int) $item->actions->where('action', 'reject')->sum('quantity');

        $refundSum = (float) $item->actions->where('action', 'refund')->sum('refund_amount');

        $item->qty_exchange  = $ex;
        $item->qty_refund    = $rf;
        $item->qty_reject    = $rj;
        $item->refund_amount = $refundSum;

        if ($ex > 0 && $rf === 0 && $rj === 0) {
            $item->item_status = 'approved_exchange';
        } elseif ($rf > 0 && $ex === 0 && $rj === 0) {
            $item->item_status = 'approved_refund';
        } elseif ($rj > 0 && $ex === 0 && $rf === 0) {
            $item->item_status = 'rejected';
        } elseif ($ex + $rf + $rj === 0) {
            $item->item_status = 'pending';
        } else {
            $item->item_status = 'approved_mixed';
        }

        $item->save();
    }
    private function ensureEditable(ReturnRequest $rr)
    {
        // khóa khi đã tạo đơn đổi hoặc đã refund/xử lý xong
        if (!empty($rr->exchange_order_id)) {
            return back()->with('error', 'Yêu cầu đã có đơn đổi, không thể sửa các dòng xử lý nữa.');
        }
        if (in_array($rr->status, ['refunded', 'rejected'], true)) {
            return back()->with('error', 'Yêu cầu đã kết thúc, không thể chỉnh sửa.');
        }
        return null;
    }

    /**
     * Recompute tổng tiền hoàn + trạng thái cấp request.
     */
    private function recomputeRequest(ReturnRequest $rr): void
    {
        $items = $rr->items()->with('actions')->get();

        $totalRefund       = 0.0;
        $hasPositiveAction = false;
        $hasAnyAction      = false;
        $allReject         = true;

        foreach ($items as $it) {
            foreach ($it->actions as $ac) {
                $hasAnyAction = true;
                if ($ac->action !== 'reject') {
                    $allReject = false;
                    $hasPositiveAction = true;
                }
                if ($ac->action === 'refund') {
                    $totalRefund += (float) $ac->refund_amount;
                }
            }
        }

        $rr->total_refund_amount = $totalRefund;

        if ($hasPositiveAction) {
            $rr->status = $rr->status === 'pending' ? 'approved' : $rr->status;
        } elseif ($hasAnyAction && $allReject) {
            $rr->status = 'rejected';
        }
        $rr->save();
    }
}
