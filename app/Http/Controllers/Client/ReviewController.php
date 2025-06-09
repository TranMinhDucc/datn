<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);


        // // Lọc bình luận tục tĩu
        // $badWords = ['tục1', 'tục2', 'tục3']; // cập nhật danh sách từ cấm
        // $cleanComment = str_ireplace($badWords, '***', $request->comment);

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được gửi!');
    }
}
