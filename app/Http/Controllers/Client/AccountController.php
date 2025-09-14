<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\ShippingAddress;
use App\Models\Wishlist;
use App\Notifications\OrderStatusNotification;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{

    public function dashboard()
    {

        $user = Auth::user();

        $addresses = ShippingAddress::with('user')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $wishlists = Wishlist::with('product')
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->latest()
            ->get();

        $orders = Order::with(['orderItems.product']) // Load luôn product của từng item
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
        }

        $notifications = $user->notifications()
            ->where('type', OrderStatusNotification::class)
            ->latest()
            ->take(10)
            ->get();


        $provinces = Province::all(); // chỉ cần load tỉnh ban đầu

        return view('client.account.dashboard', compact('notifications', 'addresses', 'user', 'wishlists', 'orders', 'provinces'));
    }



    public function wallet()
    {
        return view('client.account.wallet');
    }

    public function changePasswordForm()
    {
        return view('client.account.change-password');
    }

    // public function changePassword(Request $request)
    // {
    //     $request->validate([
    //         'current_password' => 'required',
    //         'new_password' => 'required|min:6|confirmed',
    //     ]);

    //     $user = Auth::user();

    //     if (!Hash::check($request->current_password, $user->password)) {
    //         return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
    //     }

    //     $user->password = Hash::make($request->new_password);
    //     $user->save();

    //     return back()->with('success', 'Đổi mật khẩu thành công!');
    // }

    public function resetPasswordForm()
    {
        return view('client.auth.request-reset-password');
    }

    public function sendResetPasswordLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Đã gửi liên kết khôi phục mật khẩu đến email.')
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Hiển thị form chỉnh sửa thông tin người dùng.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('client.account.profile', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        try {
            $rules = [
                'fullname' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
                'phone' => ['nullable', 'regex:/^(0|\+84)[0-9]{9}$/'],
                'address' => 'nullable|string|max:255',
            ];

            $messages = [
                'fullname.required' => 'Vui lòng nhập họ tên.',
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Định dạng email không hợp lệ.',
                'email.unique' => 'Email đã được sử dụng.',
                'phone.regex' => 'Số điện thoại không đúng định dạng.',
            ];

            $validated = $request->validate($rules, $messages);

            $user->update($validated);

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            // Ghi log lỗi ra storage/logs/laravel.log
            \Log::error('❌ Lỗi cập nhật profile: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Trả JSON để frontend hiển thị bằng Swal
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật thông tin!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/', // Chữ hoa
                'regex:/[0-9]/', // Số
                'regex:/[\W]/',  // Ký tự đặc biệt
            ],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'new_password.regex' => 'Mật khẩu phải có ít nhất 1 chữ cái in hoa, 1 số và 1 ký tự đặc biệt.',
        ]);

        // ✅ Kiểm tra mật khẩu hiện tại có đúng không
        $validator->after(function ($validator) use ($request, $user) {
            if (!Hash::check($request->current_password, $user->password)) {
                $validator->errors()->add('current_password', 'Mật khẩu hiện tại không đúng.');
            }

            // ✅ Nếu trùng với mật khẩu cũ
            if (Hash::check($request->new_password, $user->password)) {
                $validator->errors()->add('new_password', 'Mật khẩu mới phải khác mật khẩu hiện tại.');
            }

            // ✅ Kiểm tra xác nhận mật khẩu
            if ($request->new_password !== $request->new_password_confirmation) {
                $validator->errors()->add('new_password_confirmation', 'Xác nhận mật khẩu không khớp.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // ✅ Đổi mật khẩu
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => true]);
    }
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = 'uploads/avatars';

            // Tạo thư mục nếu chưa có
            if (!file_exists(public_path($path))) {
                mkdir(public_path($path), 0755, true);
            }

            // Lưu file
            $file->move(public_path($path), $filename);

            // Nếu có avatar cũ thì xoá
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            // Cập nhật avatar
            $user->avatar = $path . '/' . $filename;
            $user->save();

            return redirect()->back()->with([
                'success' => 'Ảnh đại diện đã được cập nhật!',
                'action' => 'avatar'
            ]);
        }

        return redirect()->back()->with('error', 'Vui lòng chọn ảnh hợp lệ.');
    }
}
