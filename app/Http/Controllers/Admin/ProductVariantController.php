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
}
