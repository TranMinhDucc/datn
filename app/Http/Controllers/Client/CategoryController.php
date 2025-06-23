<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    //
    public function show($id)
    {
        // Lấy danh mục cha
        $category = Category::with('products')->findOrFail($id);

        // Lấy ID các danh mục con
        // $childCategoryIds = Category::where('parent_id', $category->id)->pluck('id');

        // // Bao gồm cả danh mục cha và con để lọc sản phẩm
        // $categoryIds = $childCategoryIds->push($category->id);

        // // Lấy sản phẩm thuộc các danh mục này
        // $products = Product::whereIn('category_id', $categoryIds)->paginate(12);
        return view('client.categories.show', compact('category'));
    }

    public function index()
    {
        return view('client.categories.index');
    }
}
