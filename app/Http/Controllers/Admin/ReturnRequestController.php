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

        // Chặn nếu yêu cầu đã xử lý
        if ($returnRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Yêu cầu đổi hàng đã được xử lý trước đó.');
        }

        try {
            $returnRequest->status = 'exchanged';
            $returnRequest->save();

            return redirect()->route('admin.orders.show', $returnRequest->order_id)
                ->with('success', 'Đã xử lý đổi hàng thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi khi xử lý: ' . $e->getMessage());
        }
    }

    public function showExchangeOrderForm($id)
    {
        $returnRequest = ReturnRequest::with('items.orderItem.product.variants', 'order')->findOrFail($id);

        // Nếu đơn hàng gốc là đơn đổi → không cho đổi tiếp
        if ($returnRequest->order && $returnRequest->order->is_exchange) {
            return redirect()->back()->with('error', 'Không thể tiếp tục đổi hàng cho đơn hàng đã được đổi.');
        }

        $user = $returnRequest->order->user ?? null;
        $addresses = $user ? $user->shippingAddresses : [];

        $products = Product::with('variants')->get();
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
            'users' => [$user],
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
            'order'
        ])->findOrFail($id);

        if ($returnRequest->order && $returnRequest->order->is_exchange) {
            return redirect()->back()->with('error', 'Không thể đổi đơn hàng đã là đơn đổi.');
        }

        $user = $returnRequest->order->user;
        $users = User::all();
        $products = Product::with('variants')->get();
        $productVariants = [];

        foreach ($products as $product) {
            $productVariants[$product->id] = $product->variants;
        }

        $addresses = $user ? $user->shippingAddresses : collect();
        $paymentMethods = PaymentMethod::all();
        $shippingMethods = ShippingMethod::all();
        $selectedAddressId =
            optional($returnRequest->order)->address_id
            ?? optional($addresses->firstWhere('is_default', 1))->id
            ?? optional($addresses->first())->id;
        return view('admin.return_requests.exchange_create', [
            'returnRequest' => $returnRequest,
            'user' => $user,
            'users' => $users,
            'addresses' => $addresses,
            'selectedAddressId' => $selectedAddressId,
            'products' => $products,
            'productVariants' => $productVariants,
            'paymentMethods' => $paymentMethods,
            'shippingMethods' => $shippingMethods,
        ]);
    }

    public function createExchangeOrder(Request $request, $id)
    {
        $returnRequest = ReturnRequest::with('order')->findOrFail($id);

        // Chặn nếu đơn gốc là đơn đổi
        if ($returnRequest->order && $returnRequest->order->is_exchange) {
            return redirect()->back()->with('error', 'Không thể tiếp tục đổi đơn hàng đã là đơn đổi.');
        }

        // Chặn nếu yêu cầu đã xử lý
        if ($returnRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Yêu cầu đổi hàng này đã được xử lý.');
        }

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

        $subtotal = 0;

        foreach ($request->input('items') as $item) {
            $product = Product::findOrFail($item['product_id']);
            $variant = isset($item['variant_id']) ? ProductVariant::find($item['variant_id']) : null;

            // Lấy giá sản phẩm
            $price = $variant?->price ?? $product->sale_price ?? $product->price ?? 0;
            $lineTotal = $price * $item['quantity'];
            $subtotal += $lineTotal;

            // Trừ tồn kho
            if ($variant) {
                // Sản phẩm có biến thể
                if ($variant->quantity < $item['quantity']) {
                    return redirect()->back()->with('error', "Biến thể {$variant->variant_name} không đủ tồn kho.");
                }
                $variant->quantity -= $item['quantity'];
                $variant->save();
            } else {
                // Sản phẩm không có biến thể
                if ($product->stock_quantity < $item['quantity']) {
                    return redirect()->back()->with('error', "Sản phẩm {$product->name} không đủ tồn kho.");
                }
                $product->stock_quantity -= $item['quantity'];
                $product->save();
            }

            // Thêm vào order_items
            $order->orderItems()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'quantity' => $item['quantity'],
                'product_name' => $product->name,
                'variant_name' => $variant?->variant_name ?? null,
                'sku' => $variant?->sku ?? $product->sku ?? null,
                'price' => $price,
                'discount' => 0,
                'tax_amount' => 0,
                'total_price' => $lineTotal,
            ]);
        }

        // Cập nhật tổng tiền
        $order->subtotal = $subtotal;
        $order->total_amount = $subtotal + $order->shipping_fee;
        $order->save();

        // Cập nhật yêu cầu đổi
        $returnRequest->status = 'exchanged';
        $returnRequest->exchange_order_id = $order->id;
        $returnRequest->save();

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Đơn hàng đổi đã được tạo thành công và đã trừ tồn kho.');
    }
}
