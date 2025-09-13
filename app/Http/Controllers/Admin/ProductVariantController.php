<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index()
    {
        $variants = ProductVariant::with(['product', 'attributes'])->paginate(10);
        return view('admin.product_variants.index', compact('variants'));
    }


    public function toggleStatus(ProductVariant $variant)
    {
        // (Tuỳ: thêm authorize nếu cần)
        $variant->is_active = !$variant->is_active;
        $variant->save();

        return response()->json([
            'status' => 'success',
            'is_active' => (bool) $variant->is_active,
        ]);
    }

    public function destroy(ProductVariant $variant)
    {
        if ($variant->hasOrders()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể xóa biến thể đã có đơn hàng'
            ], 400);
        }

        $variant->delete();

        return response()->json(['status' => 'success']);
    }
}
