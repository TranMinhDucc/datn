<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ReturnRequest;
use App\Models\ReturnRequestItem;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    // public function createExchangeOrder(Request $request, $id)
    // {
    //     $returnRequest = ReturnRequest::with(['order.user', 'items.orderItem'])->findOrFail($id);

    //     if ($returnRequest->status !== ReturnRequest::STATUS_APPROVED) {
    //         return back()->with('error', 'Yêu cầu chưa được duyệt hoặc đã xử lý.');
    //     }

    //     DB::transaction(function () use ($returnRequest) {
    //         $order = Order::create([
    //             'user_id' => $returnRequest->order->user_id,
    //             'order_code' => 'EX-' . now()->format('YmdHis'),
    //             'address_id' => $returnRequest->order->address_id,
    //             'payment_method_id' => $returnRequest->order->payment_method_id,
    //             'shipping_fee' => 0,
    //             'subtotal' => 0,
    //             'total_amount' => 0,
    //             'status' => 'pending',
    //             'is_exchange' => true,
    //         ]);

    //         $subtotal = 0;

    //         foreach ($returnRequest->items()->where('status', ReturnRequestItem::STATUS_APPROVED)->get() as $item) {
    //             $product = $item->orderItem->product;
    //             $variant = $item->orderItem->productVariant;
    //             $price = $variant?->price ?? $product->price;

    //             $lineTotal = $price * $item->approved_quantity;
    //             $subtotal += $lineTotal;

    //             $order->orderItems()->create([
    //                 'product_id' => $product->id,
    //                 'product_variant_id' => $variant?->id,
    //                 'quantity' => $item->approved_quantity,
    //                 'product_name' => $product->name,
    //                 'variant_name' => $variant?->variant_name,
    //                 'sku' => $variant?->sku ?? $product->sku,
    //                 'price' => $price,
    //                 'total_price' => $lineTotal,
    //             ]);

    //             // Trừ tồn kho
    //             if ($variant) {
    //                 $variant->decrement('stock', $item->approved_quantity);
    //             } else {
    //                 $product->decrement('stock', $item->approved_quantity);
    //             }

    //             $item->update(['status' => ReturnRequestItem::STATUS_EXCHANGED]);
    //         }

    //         $order->update([
    //             'subtotal' => $subtotal,
    //             'total_amount' => $subtotal,
    //         ]);

    //         $returnRequest->update([
    //             'status' => ReturnRequest::STATUS_EXCHANGED,
    //             'exchange_order_id' => $order->id,
    //             'handled_by' => auth()->id(),
    //             'handled_at' => now(),
    //         ]);
    //     });

    //     return back()->with('success', '✅ Đã tạo đơn hàng đổi.');
    // }

    public function reject($id, Request $request)
    {
        $returnRequest = ReturnRequest::findOrFail($id);

        if ($returnRequest->status !== ReturnRequest::STATUS_PENDING) {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }

        $request->validate([
            'reason' => 'required|string|min:3',
        ]);

        $returnRequest->status = ReturnRequest::STATUS_REJECTED;
        $returnRequest->admin_note = $request->input('reason');
        $returnRequest->save();

        return back()->with('success', '❌ Đã từ chối yêu cầu đổi hàng.');
    }

    public function approve($id, Request $request)
    {
        $returnRequest = ReturnRequest::with('items')->findOrFail($id);

        if ($returnRequest->status !== ReturnRequest::STATUS_PENDING) {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }

        // duyệt từng item
        $data = $request->input('items', []);
        foreach ($returnRequest->items as $item) {
            $approvedQty = $data[$item->id]['approved_quantity'] ?? 0;
            $item->update([
                'approved_quantity' => $approvedQty,
                'status' => $approvedQty > 0 ? ReturnRequestItem::STATUS_APPROVED : ReturnRequestItem::STATUS_REJECTED,
            ]);
        }

        // Cập nhật trạng thái cha
        if ($returnRequest->items()->where('status', ReturnRequestItem::STATUS_APPROVED)->exists()) {
            $returnRequest->update([
                'status' => ReturnRequest::STATUS_APPROVED,
                'admin_note' => $request->input('note', 'Đã duyệt yêu cầu'),
                'handled_by' => auth()->id(),
                'handled_at' => now(),
            ]);
        } else {
            $returnRequest->update([
                'status' => ReturnRequest::STATUS_REJECTED,
                'admin_note' => $request->input('note', 'Không có sản phẩm nào được duyệt'),
                'handled_by' => auth()->id(),
                'handled_at' => now(),
            ]);
        }

        return back()->with('success', '✅ Đã duyệt yêu cầu.');
    }



    /**
     * HOÀN TIỀN
     */
    public function refund($id, Request $request)
    {
        $returnRequest = ReturnRequest::with('items.orderItem')->findOrFail($id);

        if (!in_array($returnRequest->status, [ReturnRequest::STATUS_APPROVED, ReturnRequest::STATUS_PENDING])) {
            return back()->with('error', 'Yêu cầu này không thể hoàn tiền.');
        }

        DB::transaction(function () use ($returnRequest, $request) {
            $totalRefund = 0;

            foreach ($returnRequest->items()->where('status', ReturnRequestItem::STATUS_APPROVED)->get() as $item) {
                $line = $item->approved_quantity * ($item->orderItem->price ?? 0);
                $item->update(['status' => ReturnRequestItem::STATUS_REFUNDED]);
                $totalRefund += $line;
            }

            $returnRequest->update([
                'status' => ReturnRequest::STATUS_REFUNDED,
                'total_refund_amount' => $totalRefund,
                'admin_note' => $request->input('note', 'Đã hoàn tiền cho khách'),
                'handled_by' => auth()->id(),
                'handled_at' => now(),
            ]);
        });

        return back()->with('success', '💸 Đã hoàn tiền cho khách.');
    }
    public function createExchangeOrder(Request $request, $id)
    {
        $returnRequest = ReturnRequest::with(['order.user', 'items.orderItem'])->findOrFail($id);

        if ($returnRequest->status !== ReturnRequest::STATUS_APPROVED) {
            return back()->with('error', 'Yêu cầu chưa được duyệt hoặc đã xử lý.');
        }

        DB::transaction(function () use ($returnRequest) {
            $order = Order::create([
                'user_id' => $returnRequest->order->user_id,
                'order_code' => 'EX-' . now()->format('YmdHis'),
                'address_id' => $returnRequest->order->address_id,
                'payment_method_id' => $returnRequest->order->payment_method_id,
                'subtotal' => 0,
                'total_amount' => 0,
                'status' => 'pending',
                'is_exchange' => true,
            ]);

            $subtotal = 0;
            foreach ($returnRequest->items()->where('status', ReturnRequestItem::STATUS_APPROVED)->get() as $item) {
                $product = $item->orderItem->product;
                $variant = $item->orderItem->productVariant;
                $price = $variant?->price ?? $product->price;

                $lineTotal = $price * $item->approved_quantity;
                $subtotal += $lineTotal;

                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'quantity' => $item->approved_quantity,
                    'product_name' => $product->name,
                    'variant_name' => $variant?->variant_name,
                    'sku' => $variant?->sku ?? $product->sku,
                    'price' => $price,
                    'total_price' => $lineTotal,
                ]);

                // Trừ kho
                if ($variant) $variant->decrement('stock', $item->approved_quantity);
                else $product->decrement('stock', $item->approved_quantity);

                $item->update(['status' => ReturnRequestItem::STATUS_EXCHANGED]);
            }

            $order->update([
                'subtotal' => $subtotal,
                'total_amount' => $subtotal,
            ]);

            $returnRequest->update([
                'status' => ReturnRequest::STATUS_EXCHANGED,
                'exchange_order_id' => $order->id,
                'handled_by' => auth()->id(),
                'handled_at' => now(),
            ]);
        });

        return back()->with('success', '✅ Đơn hàng đổi đã được tạo.');
    }
}
