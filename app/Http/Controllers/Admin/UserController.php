<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('fullname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }


    /**
     * Hiển thị form tạo user (nếu dùng)
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Lưu user mới (nếu dùng)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'fullname' => ['required', 'string', 'max:255'],
            'phone'    => ['required', 'string', 'max:20'],
            'address'  => ['required', 'string', 'max:255'],
            'gender'   => ['nullable', 'in:Nam,Nữ,Khác'],
            'role'     => ['required', 'in:admin,user'],
            'point'    => ['required', 'integer', 'min:0'],
            'banned'   => ['required', 'in:0,1'],
            'avatar'   => ['nullable', 'image', 'max:2048'], // ảnh tối đa 2MB
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.unique'   => 'Tên đăng nhập đã tồn tại.',
            'email.required'    => 'Vui lòng nhập email.',
            'email.email'       => 'Email không đúng định dạng.',
            'email.unique'      => 'Email đã tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min'      => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'fullname.required' => 'Vui lòng nhập họ tên.',
            'phone.required'    => 'Vui lòng nhập số điện thoại.',
            'address.required'  => 'Vui lòng nhập địa chỉ.',
            'role.required'     => 'Vai trò bắt buộc.',
            'point.required'    => 'Điểm không được để trống.',
            'point.integer'     => 'Điểm phải là số nguyên.',
            'banned.required'   => 'Vui lòng chọn trạng thái.',
            'avatar.image'      => 'Tệp tải lên phải là ảnh.',
            'avatar.max'        => 'Ảnh đại diện không được vượt quá 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);
        $data['balance'] = 0;

        // Xử lý ảnh nếu có tải lên
        if ($request->hasFile('avatar')) {
            Log::info('📥 Đã nhận được file avatar');

            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Tạo thư mục nếu chưa có
            if (!Storage::exists('public/avatars')) {
                Storage::makeDirectory('public/avatars');
                Log::info('📂 Tạo thư mục: public/avatars');
            }

            // Lưu file
            $stored = $file->storeAs('public/avatars', $filename);
            if ($stored) {
                Log::info('✅ File avatar đã lưu: ' . $stored);
                $data['avatar'] = 'avatars/' . $filename;
            } else {
                Log::error('❌ Không lưu được ảnh đại diện!');
            }
        } else {
            Log::warning('⚠️ Không có file avatar được upload từ form.');
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Tạo người dùng thành công!');
    }

    /**
     * Hiển thị thông tin chi tiết user (nếu cần)
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Hiển thị form chỉnh sửa user
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin user
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email'    => ['required', 'email', 'max:255'],
            'fullname' => ['required', 'string', 'max:255'],
            'phone'    => ['required', 'string', 'max:20'],
            'address'  => ['required', 'string', 'max:255'],
            'gender'   => ['nullable', 'in:Nam,Nữ,Khác'],
            'role'     => ['required', 'in:admin,user'],
            'point'    => ['required', 'integer', 'min:0'],
            'banned'   => ['required', 'in:0,1'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email'    => 'Email không đúng định dạng.',
            'email.max'      => 'Email không được vượt quá 255 ký tự.',
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.unique'   => 'Tên đăng nhập đã tồn tại.',
            'fullname.required' => 'Vui lòng nhập họ tên.',
            'phone.required'    => 'Vui lòng nhập số điện thoại.',
            'address.required'  => 'Vui lòng nhập địa chỉ.',
            'role.required'     => 'Vai trò bắt buộc.',
            'point.required'    => 'Điểm không được để trống.',
            'banned.required'   => 'Vui lòng chọn trạng thái.',
        ]);

        // Thêm lỗi thủ công nếu admin thay đổi email
        if ($request->email !== $user->email) {
            $validator->after(function ($validator) {
                $validator->errors()->add('email', 'Không được thay đổi địa chỉ email.');
            });
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput(); // giữ lại tất cả input, kể cả email sai
        }

        $data = $validator->validated();

        // Ép lại email cũ vào $data để chắc chắn không thay đổi
        $data['email'] = $user->email;

        // Nếu có nhập mật khẩu mới
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    /**
     * Xóa user
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Đã xoá người dùng thành công!');
    }

    /**
     * Bật/tắt trạng thái banned (dùng toggle)
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy người dùng']);
        }

        $user->banned = $request->input('banned') ? 1 : 0;
        $user->save();

        return response()->json(['success' => true]);
    }
}
