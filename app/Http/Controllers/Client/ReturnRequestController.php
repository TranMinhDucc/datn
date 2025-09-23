<?php

namespace App\Http\Controllers\Client;

use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ReturnRequest;
use App\Models\ReturnRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function Psy\debug;

class ReturnRequestController extends Controller
{
    public function index()
    {
        $requests = ReturnRequest::with('order')
            ->whereHas('order', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('client.account.return_requests.index', compact('requests'));
    }
    public function show(ReturnRequest $returnRequest)
    {
        // Chỉ cho xem khi là chủ sở hữu
        if ($returnRequest->order->user_id !== auth()->id()) {
            abort(403);
        }

        $returnRequest->load('items.orderItem.productVariant.product');

        return view('client.account.return_requests.show', compact('returnRequest'));
    }

    public function showReturnForm(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }

        return view('client.orders.return_form', compact('order'));
    }

    public function create(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (
            !in_array($order->status, ['completed', 'delivered']) ||
            !$order->delivered_at ||
            now()->diffInDays($order->delivered_at) > 3
        ) {
            return redirect()->back()->with('error', 'Không thể gửi yêu cầu đổi/trả cho đơn hàng này.');
        }


        // Lấy danh sách sản phẩm trong đơn hàng
        $orderItems = $order->items; // Giả sử có quan hệ order->items

        return view('client.account.return_requests.return-form', compact('order', 'orderItems'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        Log::info($request->all());

        // Lấy các id sản phẩm trong đơn
        $validOrderItemIds = $order->items->pluck('id')->toArray();
        $orderItemQuantities = $order->items->pluck('quantity', 'id');

        // Lọc ra những sản phẩm có quantity > 0
        $validItems = collect($request->input('items', []))
            ->filter(fn($item) => isset($item['quantity']) && (int)$item['quantity'] > 0)
            ->values()
            ->all();

        if (empty($validItems)) {
            return back()->withInput()->withErrors([
                'items' => 'Bạn cần chọn ít nhất 1 sản phẩm với số lượng hợp lệ.'
            ]);
        }

        // Validate phần chính
        $request->validate([
            'type' => 'required|in:return,exchange',
            'reason' => 'required|string|max:1000',
            'attachments.*' => 'nullable|file|max:5120|mimetypes:image/jpeg,image/png,video/mp4,video/webm',
        ]);

        // Validate từng item
        foreach ($validItems as $i => $item) {
            $id = $item['id'] ?? null;
            $qty = $item['quantity'] ?? 0;

            if (!in_array($id, $validOrderItemIds)) {
                return back()->withInput()->withErrors([
                    "items.$i.id" => 'Sản phẩm không hợp lệ.'
                ]);
            }

            $maxQty = $orderItemQuantities[$id] ?? 0;
            if ($qty > $maxQty) {
                return back()->withInput()->withErrors([
                    "items.$i.quantity" => "Số lượng yêu cầu không được vượt quá số lượng đã mua ($maxQty)."
                ]);
            }
        }

        // Xử lý file
        $paths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $paths[] = $file->store('returns', 'public');
            }
        }

        // Tạo ReturnRequest
        $returnRequest = ReturnRequest::create([
            'order_id' => $order->id,
            'type' => $request->type,
            'reason' => $request->reason,
            'attachments' => json_encode($paths),
            'status' => 'pending',
        ]);

        foreach ($validItems as $item) {
            ReturnRequestItem::create([
                'return_request_id' => $returnRequest->id,
                'order_item_id' => $item['id'],
                'quantity' => $item['quantity'],
            ]);
        }
        // Nếu là đổi hàng thì cập nhật trạng thái đơn hàng
        if ($request->type === 'exchange') {
            $order->update(['status' => 'exchange_requested']);
        } elseif ($request->type === 'return') {
            $order->update(['status' => 'return_requested']);
        }

        return redirect()->route('client.account.dashboard')
            ->with('success', 'Đã gửi yêu cầu hoàn / đổi hàng thành công.');
    }
}
