<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\ReturnRequest;
use App\Models\ReturnRequestItem;
use App\Models\ReturnRequestItemAction;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Refund;

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

        // Merge lại để '' thành null (tránh lỗi exists khi giữ SKU hiện tại)
        $request->merge([
            'exchange_variant_id' => $request->input('exchange_variant_id') ?: null,
        ]);

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

        // Lấy variant đích với exchange (có thể null = giữ SKU hiện tại)
        $exchangeVariantId = $data['action'] === 'exchange' ? ($data['exchange_variant_id'] ?? null) : null;

        DB::transaction(function () use ($item, $rr, $data, $qtyNew, $exchangeVariantId, $oi) {
            // Nếu là refund mà không nhập tiền thì tính theo đơn giá
            $unitPaid = $item->unit_price_paid ?? ($oi->total_price / max(1, $oi->quantity));
            $refundAmount = null;

            if ($data['action'] === 'refund') {
                $refundAmount = $data['refund_amount'] ?? null;
                if ($refundAmount === null) {
                    $refundAmount = round($unitPaid * $qtyNew, 2);
                }
            }

            // Nếu số lượng > 1 => tạo nhiều record (mỗi cái 1 record)
            for ($i = 0; $i < $qtyNew; $i++) {
                $action = new ReturnRequestItemAction();
                $action->return_request_item_id = $item->id;
                $action->action                 = $data['action'];
                $action->quantity               = 1; // luôn 1 để QC riêng từng sp
                $action->exchange_variant_id    = $exchangeVariantId;
                $action->refund_amount          = $refundAmount ? round($refundAmount / $qtyNew, 2) : null; // prorate nếu refund
                $action->note                   = $data['note'] ?? null;
                $action->created_by             = auth()->id();
                $action->updated_by             = null;
                $action->save();
            }

            // Cộng gộp lại
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
        $action = ReturnRequestItemAction::with(['item.orderItem', 'item.returnRequest', 'item.actions'])
            ->findOrFail($actionId);

        $item = $action->item;
        $rr   = $item->returnRequest;
        $oi   = $item->orderItem;

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

        // Tổng của item này (trừ action đang sửa)
        $sumThisItemOthers = (int) $item->actions()->where('id', '!=', $action->id)->sum('quantity');

        // Tổng đã dùng cho CÙNG order_item ở item khác
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

        // Refund tính lại tiền
        $refundAmount = null;
        $isManual     = false;

        if ($data['action'] === 'refund') {
            $unitPaid = $item->unit_price_paid ?? (float) ($oi->total_price / max(1, $oi->quantity));
            $autoAmt  = round($unitPaid * $qtyNew, 2);

            $isManual     = $request->filled('refund_amount');
            $refundAmount = $isManual ? (float) $data['refund_amount'] : $autoAmt;
        }

        DB::transaction(function () use ($action, $item, $rr, $data, $qtyNew, $exchangeVariantId, $refundAmount, $isManual) {
            // Xoá action cũ
            $action->delete();

            // Nếu quantity > 1 → tạo nhiều record (mỗi cái = 1)
            for ($i = 0; $i < $qtyNew; $i++) {
                $newAct = new ReturnRequestItemAction();
                $newAct->return_request_item_id = $item->id;
                $newAct->action                 = $data['action'];
                $newAct->quantity               = 1;
                $newAct->exchange_variant_id    = $exchangeVariantId;
                $newAct->refund_amount          = $refundAmount ? round($refundAmount / $qtyNew, 2) : null;
                $newAct->is_manual_amount       = $isManual;
                $newAct->note                   = $data['note'] ?? null;
                $newAct->created_by             = $action->created_by;
                $newAct->updated_by             = auth()->id();
                $newAct->save();
            }

            // Recompute
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
        $action = ReturnRequestItemAction::with(['item.returnRequest', 'item.actions'])->findOrFail($actionId);
        $item   = $action->item;
        $rr     = $item->returnRequest;

        // KHÓA tập trung
        if ($resp = $this->ensureEditable($rr)) return $resp;

        return DB::transaction(function () use ($action) {
            // Recheck trong transaction (lock)
            $rrLocked = ReturnRequest::lockForUpdate()->find($action->item->return_request_id);
            if ($resp = $this->ensureEditable($rrLocked)) return $resp;

            $item = $action->item; // giữ lại để recompute
            $action->delete();

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
    private function ensureEditable(ReturnRequest $rr, string $mode = 'full')
    {
        // 1. Nếu có phiếu hoàn đã DONE → khóa toàn bộ
        $hasRefundDone = Refund::where('return_request_id', $rr->id)
            ->where('status', 'done')
            ->exists();
        if ($hasRefundDone) {
            return back()->with('error', 'Yêu cầu đã có phiếu hoàn tất (done), không thể chỉnh sửa.');
        }

        // 2. Nếu có phiếu hoàn PENDING
        if ($mode === 'full') {
            $hasRefundPending = Refund::where('return_request_id', $rr->id)
                ->where('status', 'pending')
                ->exists();
            if ($hasRefundPending) {
                return back()->with('error', 'Yêu cầu đã có phiếu hoàn chờ xử lý (pending), không thể thay đổi hành động.');
            }
        }

        // 3. Nếu đã có đơn đổi
        if (!empty($rr->exchange_order_id)) {
            return back()->with('error', 'Yêu cầu đã có đơn đổi, không thể chỉnh sửa.');
        }

        // 4. Nếu RMA đã kết thúc hoàn tiền
        if ($rr->status === 'refunded') {
            return back()->with('error', 'Yêu cầu đã hoàn tiền xong, không thể chỉnh sửa.');
        }

        return null; // OK
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

                // Chỉ tính các action QC đạt
                if (in_array($ac->action, ['refund', 'exchange'])) {
                    $isPassed = str_starts_with($ac->qc_status ?? '', 'passed');
                    if ($isPassed) {
                        $hasPositiveAction = true;
                        if ($ac->action === 'refund') {
                            $totalRefund += (float) $ac->refund_amount;
                        }
                    }
                } elseif ($ac->action === 'reject') {
                }
            }
        }


        $rr->total_refund_amount = $totalRefund;

        if ($hasPositiveAction) {
            // Nếu có cả hoàn và đổi
            $hasRefund   = $items->sum(fn($it) => $it->actions->where('action', 'refund')->count()) > 0;
            $hasExchange = $items->sum(fn($it) => $it->actions->where('action', 'exchange')->count()) > 0;

            if ($hasRefund && $hasExchange) {
                $rr->status = 'exchange_and_refund_processing';
            } elseif ($hasExchange) {
                $rr->status = 'exchange_in_progress';
            } elseif ($hasRefund) {
                $rr->status = 'refund_processing';
            } else {
                $rr->status = 'approved';
            }
        } elseif ($hasAnyAction && $allReject) {
            $rr->status = 'rejected_temp'; // chỉ QC fail, chưa chốt
        }

        $rr->save();
    }
    public function updateQC(Request $request, $actionId)
    {
        $action = ReturnRequestItemAction::with('item.returnRequest')->findOrFail($actionId);
        $rr     = $action->item->returnRequest;
        if ($action->action === 'reject') {
            return back()->with('error', '❌ Hành động từ chối không yêu cầu QC.');
        }


        // KHÓA nếu request đã kết thúc
        if ($resp = $this->ensureEditable($rr)) return $resp;

        $data = $request->validate([
            'qc_status' => ['required', Rule::in(['passed_import', 'passed_noimport', 'failed'])],
            'qc_note'   => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($action, $data) {
            $oi = $action->item->orderItem;
            $variant = $oi->productVariant;
            $beforeQty = $variant ? $variant->quantity : 0;
            $afterQty  = $beforeQty; // mặc định không đổi
            // Nếu trước đó đã có QC status khác thì rollback tồn kho
            if ($action->qc_status === 'passed_import') {
                // rollback nhập kho
                InventoryTransaction::create([
                    'product_id'         => $oi->product_id,
                    'product_variant_id' => $oi->product_variant_id,
                    'type'               => 'adjust',
                    'quantity'           => -$action->quantity,
                    'before_quantity'  => $beforeQty,
                    'after_quantity'   => $afterQty,
                    'note'               => "Rollback QC Passed (Nhập kho) - RR #{$action->item->return_request_id}",
                    'created_by'         => auth()->id(),
                ]);
                $oi->productVariant->decrement('quantity', $action->quantity);
            } elseif ($action->qc_status === 'failed') {
                // rollback discard (thực tế không cộng kho, chỉ để lưu lịch sử audit)
                InventoryTransaction::create([
                    'product_id'         => $oi->product_id,
                    'product_variant_id' => $oi->product_variant_id,
                    'type'               => 'adjust',
                    'quantity'           => +$action->quantity,
                    'before_quantity'  => $beforeQty,
                    'after_quantity'   => $afterQty,
                    'note'               => "Rollback QC Failed (Discard) - RR #{$action->item->return_request_id}",
                    'created_by'         => auth()->id(),
                ]);
                // ❌ Không chỉnh stock thật, vì discard không ảnh hưởng tới tồn kho
            }

            // Cập nhật trạng thái QC mới
            $action->qc_status = $data['qc_status'];
            $action->qc_note   = $data['qc_note'] ?? null;
            $action->save();

            // Ghi transaction theo trạng thái mới
            // if ($data['qc_status'] === 'passed_import') {
            //     if ($action->action === 'refund') {
            //         InventoryTransaction::create([
            //             'product_id'         => $oi->product_id,
            //             'product_variant_id' => $oi->product_variant_id,
            //             'type'               => 'import',
            //             'quantity'           => $action->quantity,
            //             'note'               => "QC Passed (Nhập kho) - RR #{$action->item->return_request_id}",
            //             'created_by'         => auth()->id(),
            //         ]);
            //         $oi->productVariant->increment('quantity', $action->quantity);
            //     }
            //     // Nếu exchange thì chưa nhập lại kho, chỉ xử lý khi tạo đơn đổi
            // } elseif ($data['qc_status'] === 'passed_noimport') {
            //     // QC đạt nhưng KHÔNG nhập kho → không ảnh hưởng stock
            // } elseif ($data['qc_status'] === 'failed') {
            //     InventoryTransaction::create([
            //         'product_id'         => $oi->product_id,
            //         'product_variant_id' => $oi->product_variant_id,
            //         'type'               => 'discard',
            //         'quantity'           => $action->quantity,
            //         'note'               => "QC Failed - loại bỏ hàng từ RR #{$action->item->return_request_id}",
            //         'created_by'         => auth()->id(),
            //     ]);
            //     // discard chỉ để ghi nhận → không thay đổi tồn kho thật
            // }
            if ($data['qc_status'] === 'passed_import') {
                if (in_array($action->action, ['refund', 'exchange'])) {
                    $beforeQty = $oi->productVariant->quantity;

                    // tăng tồn kho
                    $oi->productVariant->increment('quantity', $action->quantity);

                    // sau khi tăng, lấy lại số lượng
                    $afterQty = $oi->productVariant->quantity;
                    InventoryTransaction::create([
                        'product_id'         => $oi->product_id,
                        'product_variant_id' => $oi->product_variant_id,
                        'type'               => 'import',
                        'quantity'           => $action->quantity,
                        'before_quantity'  => $beforeQty,
                        'after_quantity'   => $afterQty,
                        'note'               => "QC Passed (Nhập kho) - RR #{$action->item->return_request_id}",
                        'created_by'         => auth()->id(),
                    ]);
                }
            } elseif ($data['qc_status'] === 'passed_noimport') {
                InventoryTransaction::create([
                    'product_id'         => $oi->product_id,
                    'product_variant_id' => $oi->product_variant_id,
                    'type'               => 'discard',
                    'quantity'           => $action->quantity,
                    'before_quantity'  => $beforeQty,
                    'after_quantity'   => $afterQty,
                    'note'               => "Chất lượng sản phẩm đạt (Không nhập kho - lỗi từ NSX, ghi nhận loại bỏ) - RR #{$action->item->return_request_id}",
                    'created_by'         => auth()->id(),
                ]);
                // ❌ không cộng stock, chỉ log loại bỏ
            } elseif ($data['qc_status'] === 'failed') {
                InventoryTransaction::create([
                    'product_id'         => $oi->product_id,
                    'product_variant_id' => $oi->product_variant_id,
                    'type'               => 'discard',
                    'quantity'           => $action->quantity,
                    'before_quantity'  => $beforeQty,
                    'after_quantity'   => $afterQty,
                    'note'               => "QC Failed (Loại bỏ) - RR #{$action->item->return_request_id}",
                    'created_by'         => auth()->id(),
                ]);
                // ❌ không cộng stock
            }
        });

        return back()->with('success', 'Đã cập nhật QC cho hành động.');
    }
}
