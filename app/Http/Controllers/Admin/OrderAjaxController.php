<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderAjaxController extends Controller
{
    /**
     * API cho Select2: liệt kê tất cả đơn (phân trang + tìm kiếm),
     * kèm flag "refundable" nếu đủ điều kiện hoàn MoMo.
     *
     * GET /admin/ajax/orders/search?q=...&page=1
     * Trả về: { results: [{id,text,refundable,left}], pagination: { more: bool } }
     */
    // Liệt kê tất cả đơn (có/không MoMo), có phân trang + tìm kiếm
public function searchAllWithRefundable(Request $r)
{
    $q       = trim($r->get('q',''));
    $page    = max(1, (int)$r->get('page',1));
    $perPage = 20;

    $refundedSub = Refund::select('order_id', DB::raw('SUM(amount) AS refunded_sum'))
        ->where('status', '!=', 'canceled')
        ->groupBy('order_id');

    $base = Order::query()
        ->leftJoinSub($refundedSub, 'rf', 'rf.order_id', '=', 'orders.id')
        ->select([
            'orders.id',
            'orders.order_code',
            'orders.momo_trans_id',
            'orders.total_amount',
            'orders.status',
            DB::raw('COALESCE(rf.refunded_sum,0) AS refunded_sum'),
            DB::raw("CASE WHEN orders.momo_trans_id IS NULL OR orders.momo_trans_id = '' THEN 'Khác' ELSE 'MoMo' END AS method_name"),
        ])
        ->when($q !== '', function ($qq) use ($q) {
            $qq->where(function ($w) use ($q) {
                $w->where('orders.id', $q)->orWhere('orders.order_code', 'like', "%{$q}%");
            });
        })
        ->orderByDesc('orders.id');

    $total  = (clone $base)->count();
    $orders = $base->skip(($page-1)*$perPage)->take($perPage)->get();

    $results = $orders->map(function ($o) {
        $isMomo = !empty($o->momo_trans_id);
        $paid   = $isMomo ? (float)$o->total_amount : 0.0;   // ✅ COD => 0
        $left   = max(0, $paid - (float)$o->refunded_sum);

        $label = sprintf(
            '%s — %sđ — %s%s',
            $o->order_code ?: ('#'.$o->id),
            number_format((float)$o->total_amount, 0, ',', '.'),
            $o->method_name,
            $left > 0 ? (' — còn: '.number_format($left, 0, ',', '.').'đ') : ''
        );

        return [
            'id'                => $o->id,
            'text'              => $label,
            'left'              => $left,
            'method'            => $o->method_name,
            'momo_refundable'   => ($o->status === 'cancelled') && $isMomo && $left > 0,
            'manual_refundable' => $left > 0,
        ];
    });

    return response()->json([
        'results'    => $results,
        'pagination' => ['more' => ($page * $perPage) < $total],
    ]);
}


// Tóm tắt 1 đơn để auto-điền số tiền hoàn
public function lookup(Request $r)
{
    $key = trim((string) $r->get('order_id'));

    $refundedSub = \App\Models\Refund::select('order_id', \DB::raw('SUM(amount) AS refunded_sum'))
        ->where('status', '!=', 'canceled')
        ->groupBy('order_id');

    $o = \DB::table('orders')
        ->leftJoin('users', 'users.id', '=', 'orders.user_id')
        ->leftJoinSub($refundedSub, 'rf', 'rf.order_id', '=', 'orders.id')
        ->select(
            'orders.id',
            'orders.order_code',
            'orders.total_amount',
            'orders.momo_trans_id',
            'orders.status',
            \DB::raw("COALESCE(users.fullname,'') AS user_name"),
            \DB::raw('COALESCE(rf.refunded_sum,0) AS refunded_sum'),
            \DB::raw("CASE WHEN orders.momo_trans_id IS NULL OR orders.momo_trans_id = '' THEN 'Khác' ELSE 'MoMo' END AS method_name")
        )
        ->where(function ($q) use ($key) {
            if (ctype_digit($key)) $q->where('orders.id', $key);
            else $q->where('orders.order_code', $key);
        })
        ->first();

    if (!$o) return response()->json(['ok' => false, 'error' => 'ORDER_NOT_FOUND']);

    $isMomo = !empty($o->momo_trans_id);
    $paid   = $isMomo ? (float)$o->total_amount : 0.0;      // ✅ COD => 0
    $left   = max(0, $paid - (float)$o->refunded_sum);

    return response()->json([
        'ok'               => true,
        'code'             => $o->order_code ?? $o->id,
        'orderTotal'       => (float)$o->total_amount,
        'paidAmount'       => $paid,
        'refundableLeft'   => $left,
        'momoTransId'      => $o->momo_trans_id,
        'paymentMethod'    => $o->method_name,
        'customer'         => ['name' => $o->user_name],
        'isRefundable'     => $left > 0,
        'isMomoRefundable' => ($o->status === 'cancelled') && $isMomo && $left > 0,
    ]);
}

}
