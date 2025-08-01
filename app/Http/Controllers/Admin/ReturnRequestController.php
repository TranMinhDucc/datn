<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ReturnRequest;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    public function handleExchange($id)
    {
        $returnRequest = ReturnRequest::with(['items.orderItem'])->findOrFail($id);

        // Kiểm tra trạng thái đã xử lý chưa
        if ($returnRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Yêu cầu đổi hàng đã được xử lý trước đó.');
        }

        // TODO: Thêm logic tạo đơn hàng đổi mới (nếu cần) dựa trên $returnRequest->items

        try {
            // Cập nhật trạng thái
            $returnRequest->status = 'exchanged'; // ✔ Đảm bảo là chuỗi
            $returnRequest->save();

            return redirect()->route('admin.orders.show', $returnRequest->order_id)
                ->with('success', 'Đã xử lý đổi hàng thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi khi xử lý: ' . $e->getMessage());
        }
    }
    public function showExchangeOrderForm($id)
    {
        $returnRequest = ReturnRequest::with('items.orderItem.product.variants')->findOrFail($id);

        // Dữ liệu user và shipping address từ đơn gốc
        $user = $returnRequest->order->user ?? null;
        $addresses = $user ? $user->shippingAddresses : [];

        // Dữ liệu các sản phẩm có thể chọn
        $products = Product::with('variants')->get();

        // Map biến thể theo ID để đổ ra js
        $productVariants = [];
        foreach ($products as $product) {
            $productVariants[$product->id] = $product->variants->map(function ($v) {
                return [
                    'id' => $v->id,
                    'variant_name' => ($v->color ?? '') . ' - ' . ($v->size ?? ''),
                    'price' => $v->price,
                ];
            });
        }

        return view('admin.return_requests.exchange_create_order', [
            'returnRequest' => $returnRequest,
            'users' => [$user], // Chỉ 1 user của đơn gốc
            'paymentMethods' => PaymentMethod::all(),
            'shippingMethods' => ShippingMethod::all(),
            'products' => $products,
            'productVariants' => $productVariants,
            'addresses' => $addresses,
        ]);
    }
    public function showExchangeForm($id)
    {
        $returnRequest = ReturnRequest::with([
            'items.orderItem.product.variants',
            'items.orderItem.variant',
            'order.user.shippingAddresses.province',
            'order.user.shippingAddresses.district',
            'order.user.shippingAddresses.ward',
        ])->findOrFail($id);

        $user = $returnRequest->order->user;

        $users = User::all();
        $products = Product::with('variants')->get();

        $productVariants = [];
        foreach ($products as $product) {
            // dd($product);
            $productVariants[$product->id] = $product->variants;
        }

        $addresses = $user ? $user->shippingAddresses : collect();
        $paymentMethods = PaymentMethod::all();
        $shippingMethods = ShippingMethod::all();

        return view('admin.return_requests.exchange_create', [
            'returnRequest' => $returnRequest,
            'user' => $user,
            'users' => $users,
            'addresses' => $addresses,
            'products' => $products,
            'productVariants' => $productVariants,
            'paymentMethods' => $paymentMethods,
            'shippingMethods' => $shippingMethods,
        ]);
    }





    public function createExchangeOrder(Request $request, $id)
    {
        $returnRequest = ReturnRequest::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'address_id' => 'required|exists:shipping_addresses,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = new Order();
        $order->user_id = $request->input('user_id');
        $order->order_code = 'OD' . now()->format('YmdHis');
        $order->address_id = $request->input('address_id');
        $order->payment_method_id = $request->input('payment_method_id');
        $order->shipping_fee = 0;
        $order->subtotal = 0;
        $order->total_amount = 0;
        $order->status = 'pending';
        $order->is_exchange = true;
        $order->save();

        foreach ($request->input('items') as $item) {
            $product = Product::findOrFail($item['product_id']); // Lấy tên sản phẩm
            $variant = isset($item['variant_id']) ? ProductVariant::find($item['variant_id']) : null;

            $order->orderItems()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'quantity' => $item['quantity'],
                'product_name' => $product->name,
                'variant_name' => $variant?->variant_name ?? null,
                'sku' => $variant?->sku ?? $product->sku ?? null,
                'price' => $variant?->price ?? $product->price ?? 0,
                'discount' => 0,
                'tax_amount' => 0,
                'total_price' => 0, // tổng tiền dòng này, có thể = quantity * price nếu cần
            ]);
        }


        $returnRequest->status = 'pending';
        $returnRequest->order_id = $order->id;
        $returnRequest->save();

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Đơn hàng đổi đã được tạo.');
    }
}
