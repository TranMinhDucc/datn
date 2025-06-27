<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\BadWord;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|integer',
        ]);

        $comment = $request->comment ?? '';
        $hasBadWords = $this->containsBadWords($comment);

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $comment,
            'parent_id' => $request->parent_id,
            'verified_purchase' => 1,
            'approved' => !$hasBadWords,
            'user_fullname' => auth()->user()->full_name,
        ]);

        if ($hasBadWords) {
            return back()->with('warning', 'Bình luận của bạn đang chờ duyệt vì chứa từ ngữ nhạy cảm. Admin sẽ kiểm tra và chỉnh sửa trước khi hiển thị.');
        }

        return back()->with('success', 'Đánh giá của bạn đã được gửi và hiển thị thành công!');
    }

 private function containsBadWords($comment)
{
    if (!$comment) return false;

    $comment = strtolower($comment);
    $badWords = BadWord::pluck('word')->toArray();

    foreach ($badWords as $word) {
        $word = trim($word);
        if ($word === '') continue;

        // Loại bỏ khoảng trắng + escape từ trước
        $chars = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);

        $escapedChars = array_map(function ($char) {
            return preg_quote($char, '/');
        }, $chars);

        $pattern = '/' . implode('[\W_]*', $escapedChars) . '/iu';

        if (preg_match($pattern, $comment)) {
            return true;
        }
    }

    return false;
}




    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['approved' => true]);

        return back()->with('success', 'Đánh giá đã được duyệt.');
    }
}
