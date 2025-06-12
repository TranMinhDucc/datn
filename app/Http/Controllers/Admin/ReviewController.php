<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use App\Models\BadWord;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
   public function index()
{
    $reviews = Review::with(['user', 'product'])->latest('created_at')->paginate(10);
    $badwords = BadWord::all()->pluck('word'); // Lấy danh sách từ khóa bị cấm

    return view('admin.reviews.index', compact('reviews', 'badwords'));
}

    public function create()
    {
        $products = Product::all();
        $users = User::all();
        return view('admin.reviews.create', compact('products', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'verified_purchase' => 'required|boolean',
            'approved' => 'required|boolean', // ✅ Thêm validate approved
        ]);

        $data = $request->only([
            'user_id',
            'product_id',
            'rating',
            'comment',
            'verified_purchase',
            'approved', // ✅ Lấy approved từ form
        ]);

        $data['created_at'] = now();

        Review::create($data);

        return redirect()->route('admin.reviews.index')->with('success', 'Đánh giá đã được thêm.');
    }

    public function show(Review $review)
    {
        return view('admin.reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $products = Product::all();
        $users = User::all();
         $badwords = BadWord::all()->pluck('word'); 
        return view('admin.reviews.edit', compact('review', 'products', 'users','badwords'));
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
            'verified_purchase' => 'required|boolean',
            'approved' => 'required|boolean', // ✅ Validate trường duyệt
        ]);

        $review->update([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'verified_purchase' => $request->verified_purchase,
            'approved' => $request->approved, // ✅ Cập nhật approved
        ]);

        return redirect()->route('admin.reviews.index')->with('success', 'Đánh giá đã được cập nhật.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Đánh giá đã được xóa.');
    }
}
