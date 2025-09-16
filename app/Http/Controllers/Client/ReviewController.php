<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
            'order_item_id' => 'required|integer|exists:order_items,id',
        ]);

        // kiểm tra xem người dùng đã mua hàng chưa thì mới được đánh giá
        if (!auth()->user()->hasPurchased($request->product_id)) {
            return back()->with('warning', 'Bạn cần phải mua sản phẩm trước khi thực hiện đánh giá');
        }

        $comment = $request->comment ?? '';
        $hasBadWords = $this->containsBadWords($comment);

        // ✅ Kiểm tra review đã tồn tại chưa
        $review = Review::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->where('order_item_id', $request->order_item_id)
            ->first();

        if ($review) {
            // Nếu có rồi thì update
            $review->update([
                'rating' => $request->rating,
                'comment' => $comment,
                'parent_id' => $request->parent_id,
                'approved' => !$hasBadWords,
            ]);

            $message = 'Đánh giá của bạn đã được cập nhật thành công!';
        } else {
            // Nếu chưa có thì tạo mới
            Review::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'order_item_id' => $request->order_item_id,
                'rating' => $request->rating,
                'comment' => $comment,
                'parent_id' => $request->parent_id,
                'verified_purchase' => 1,
                'approved' => !$hasBadWords,
                'user_fullname' => auth()->user()->full_name,
            ]);

            $message = 'Đánh giá của bạn đã được gửi thành công!';
        }

        if ($hasBadWords) {
            return back()->with('warning', 'Bình luận của bạn đang chờ duyệt vì chứa từ ngữ nhạy cảm. Admin sẽ kiểm tra và chỉnh sửa trước khi hiển thị.');
        }

        return back()->with('success', $message);
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
