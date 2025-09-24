<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class ManualRefundController extends Controller
{
    public function create()
    {
        return view('admin.manual_refund.create');
    }

    // public function store(Request $request)
    // {
    //     // amount từ client chỉ là gợi ý; server sẽ so lại với refundableLeft
    //     $request->validate([
    //         'order_id' => ['required', 'integer', 'exists:orders,id'],
    //         'amount'   => ['required', 'numeric', 'min:1000'],
    //         'note'     => ['nullable', 'string', 'max:255'],
    //     ]);

    //     try {
    //         $refund = DB::transaction(function () use ($request) {

    //             /** @var Order $order */
    //             $order = Order::whereKey($request->integer('order_id'))
    //                 ->lockForUpdate()
    //                 ->firstOrFail();

    //             // Điều kiện MoMo: có transId + đã cancelled (điều chỉnh status tuỳ hệ thống)
    //             if (empty($order->momo_trans_id)) {
    //                 throw ValidationException::withMessages([
    //                     'order_id' => 'Đơn này không có mã giao dịch MoMo.',
    //                 ]);
    //             }
    //             if (!in_array($order->status, ['cancelled', 'customer_canceled', 'cancel_approved'], true)) {
    //                 throw ValidationException::withMessages([
    //                     'order_id' => 'Trạng thái đơn chưa cho phép hoàn MoMo.',
    //                 ]);
    //             }

    //             // Tính refundableLeft
    //             $paid     = (float) ($order->paid_amount ?? $order->total_amount);
    //             $refunded = (float) $order->refunds()->where('status', '!=', 'canceled')->sum('amount');
    //             $left     = max(0, $paid - $refunded);

    //             if ($left <= 0) {
    //                 throw ValidationException::withMessages([
    //                     'amount' => 'Đơn đã hoàn đủ, không còn số tiền để hoàn.',
    //                 ]);
    //             }
    //             if ($request->float('amount') > $left) {
    //                 throw ValidationException::withMessages([
    //                     'amount' => 'Số tiền hoàn vượt quá số còn có thể hoàn (' . number_format($left, 0, ',', '.') . 'đ).',
    //                 ]);
    //             }

    //             // Tạo refund pending
    //             $refund = Refund::create([
    //                 'order_id'      => $order->id,
    //                 'user_id'       => $order->user_id,
    //                 'amount'        => $request->float('amount'),
    //                 'currency'      => 'VND',
    //                 'method'        => 'momo',
    //                 'status'        => 'pending',
    //                 'note'          => $request->input('note'),
    //                 'created_by'    => auth()->id(),
    //             ]);

    //             // Cấu hình MoMo
    //             $endpoint = config('services.momo.endpoint');
    //             $partnerCode = config('services.momo.partner_code');
    //             $accessKey   = config('services.momo.access_key');
    //             $secretKey   = config('services.momo.secret_key');

    //             if (!$endpoint || !$partnerCode || !$accessKey || !$secretKey) {
    //                 throw ValidationException::withMessages([
    //                     'order_id' => 'Thiếu cấu hình MoMo (partnerCode/accessKey/secretKey/refund_url).',
    //                 ]);
    //             }

    //             // Tạo chữ ký
    //             $requestId     = (string) now()->getTimestampMs();
    //             $refundOrderId = 'refund_' . $order->id . '_' . now()->timestamp;

    //             $rawHash   = "accessKey={$accessKey}"
    //                 . "&amount={$refund->amount}"
    //                 . "&orderId={$refundOrderId}"
    //                 . "&partnerCode={$partnerCode}"
    //                 . "&requestId={$requestId}"
    //                 . "&transId={$order->momo_trans_id}";
    //             $signature = hash_hmac('sha256', $rawHash, $secretKey);

    //             $payload = [
    //                 'partnerCode' => $partnerCode,
    //                 'orderId'     => $refundOrderId,
    //                 'requestId'   => $requestId,
    //                 'amount'      => (int) $refund->amount,
    //                 'transId'     => (string) $order->momo_trans_id,
    //                 'lang'        => 'vi',
    //                 'description' => $request->input('note') ?: "Refund for order {$order->id}",
    //                 'signature'   => $signature,
    //             ];

    //             // Gọi API
    //             $response = Http::timeout(20)->post($endpoint, $payload);
    //             $result   = $response->json();

    //             Log::info('MoMo refund response', [
    //                 'order_id' => $order->id,
    //                 'payload'  => $payload,
    //                 'result'   => $result,
    //                 'status'   => $response->status(),
    //             ]);

    //             // Cập nhật kết quả
    //             if ($response->successful() && isset($result['resultCode']) && (int) $result['resultCode'] === 0) {
    //                 $refund->update([
    //                     'status'         => 'done',
    //                     'bank_ref'       => $result['transId'] ?? null,
    //                     'transferred_at' => now(),
    //                 ]);
    //             } else {
    //                 $msg = $result['message'] ?? $result['localMessage'] ?? 'unknown';
    //                 $refund->update([
    //                     'status' => 'failed',
    //                     'note'   => trim(($refund->note ? $refund->note . "\n" : '') . "Error: {$msg}"),
    //                 ]);
    //             }

    //             return $refund;
    //         });
    //     } catch (ValidationException $ve) {
    //         throw $ve; // trả về lỗi form
    //     } catch (Throwable $e) {
    //         Log::error('Refund MoMo exception', ['error' => $e->getMessage()]);
    //         return back()->withErrors('Có lỗi khi hoàn MoMo: ' . $e->getMessage())->withInput();
    //     }

    //     return redirect()->route('admin.manual_refund.index')
    //         ->with('success', 'Đã gửi yêu cầu hoàn tiền.');
    // }

    // app/Http/Controllers/Admin/ManualRefundController.php

    public function store(Request $request)
    {
        // 1) Validate: bắt buộc bank_ref và note
        $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'amount'   => ['required', 'numeric', 'min:1000'],
            'bank_ref' => ['required', 'string', 'max:255'],
            'note'     => ['required', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                /** @var Order $order */
                $order = Order::whereKey($request->integer('order_id'))
                    ->lockForUpdate()
                    ->firstOrFail();

                // 2) Tính số tiền còn có thể hoàn
                $paid     = (float) ($order->paid_amount ?? $order->total_amount);
                $refunded = (float) $order->refunds()->where('status', '!=', 'canceled')->sum('amount');
                $left     = max(0, $paid - $refunded);

                if ($left <= 0) {
                    throw ValidationException::withMessages([
                        'amount' => 'Đơn đã hoàn đủ, không còn số tiền để hoàn.',
                    ]);
                }

                // Ép về số nguyên VND cho chắc
                $amount = (int) round($request->input('amount'));
                if ($amount > $left) {
                    throw ValidationException::withMessages([
                        'amount' => 'Số tiền hoàn vượt quá số còn có thể hoàn (' . number_format($left, 0, ',', '.') . 'đ).',
                    ]);
                }

                // 3) Tạo refund thủ công -> DONE ngay
                Refund::create([
                    'order_id'          => $order->id,
                    'user_id'           => $order->user_id,
                    'return_request_id' => null,                 // manual không gắn RR
                    'amount'            => $amount,
                    'currency'          => 'VND',
                    'method'            => 'manual_refund',
                    'status'            => 'done',               // DONE ngay
                    'note'              => 'Manual refund: ' . trim($request->input('note')),
                    'bank_ref'          => trim($request->input('bank_ref')),
                    'transferred_at'    => now(),
                    'created_by'        => auth()->id(),
                ]);
            });
        } catch (ValidationException $ve) {
            throw $ve; // trả về lỗi form bình thường
        } catch (\Throwable $e) {
            return back()->withErrors('Có lỗi khi ghi nhận hoàn tiền: ' . $e->getMessage())->withInput();
        }

        return redirect()
            ->route('admin.manual_refund.index')
            ->with('success', 'Đã ghi nhận hoàn tiền thủ công (DONE).');
    }


    public function index(Request $request)
    {
        $status = $request->string('status')->toString(); // pending|done|failed|canceled|<blank>
        $q      = trim((string)$request->get('q', ''));

        $refunds = \App\Models\Refund::query()
            ->with([
                'order:id,order_code,total_amount,momo_trans_id',
                'user:id,fullname',
            ])
            ->when($status !== '', fn($qr) => $qr->where('status', $status))
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('id', (int)$q)
                        ->orWhereHas('order', fn($qo) => $qo->where('order_code', 'like', "%{$q}%"));
                });
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.manual_refund.index', compact('refunds', 'status', 'q'));
    }

    public function show(\App\Models\Refund $manual_refund) // resource binding tên 'manual_refund'
    {
        $manual_refund->load([
            'order:id,order_code,total_amount,momo_trans_id,status',
            'user:id,fullname,email',
            'creator:id,fullname',
        ]);

        return view('admin.manual_refund.show', [
            'refund' => $manual_refund,
        ]);
    }
}
