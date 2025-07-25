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
use App\Models\BalanceTransaction;
use App\Models\UserActivityLog;
use App\Models\Transaction;

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

        $users = $query->orderBy('created_at', 'desc')->paginate(5); // ✅


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
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9_]+$/',
                'unique:users,username',
            ],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:64',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/',
            ],
            'fullname' => ['required', 'string', 'max:255'],
            'phone'    => [
                'required',
                'regex:/^(0[3|5|7|8|9])+([0-9]{8})$/',
                'unique:users,phone',
            ],
            'address'  => ['required', 'string', 'max:255'],
            'gender'   => ['nullable', 'in:Nam,Nữ,Khác'],
            'role'     => ['required', 'in:admin,user'],
            'point'    => ['required', 'integer', 'min:0'],
            'banned'   => ['required', 'in:0,1'],
            'avatar'   => ['nullable', 'image', 'max:2048'],
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.min' => 'Tên đăng nhập phải có ít nhất 3 ký tự.',
            'username.max' => 'Tên đăng nhập không được vượt quá 30 ký tự.',
            'username.regex' => 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',

            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email đã tồn tại.',

            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.max' => 'Mật khẩu không được dài quá 64 ký tự.',
            'password.regex' => 'Mật khẩu phải có ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.',

            'fullname.required' => 'Vui lòng nhập họ tên.',

            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Phải bắt đầu bằng 03, 05, 07, 08 hoặc 09 và có 10 số.',
            'phone.unique' => 'Số điện thoại đã được sử dụng.',

            'address.required' => 'Vui lòng nhập địa chỉ.',
            'role.required' => 'Vai trò bắt buộc.',
            'point.required' => 'Điểm không được để trống.',
            'point.integer' => 'Điểm phải là số nguyên.',
            'banned.required' => 'Vui lòng chọn trạng thái.',

            'avatar.image' => 'Tệp tải lên phải là ảnh.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);
        $data['balance'] = 0;

        // Xử lý ảnh nếu có
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/avatars', $filename);
            $data['avatar'] = 'avatars/' . $filename;
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
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email'    => ['required', 'email', 'max:255'],
            'fullname' => ['required', 'string', 'max:255'],
            'phone'    => [
                'required',
                'regex:/^(0[3|5|7|8|9])+([0-9]{8})$/',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'address'  => ['required', 'string', 'max:255'],
            'gender'   => ['nullable', 'in:Nam,Nữ,Khác'],
            'role'     => ['required', 'in:admin,user'],
            'point'    => ['required', 'integer', 'min:0'],
            'banned'   => ['required', 'in:0,1'],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/',
            ],
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.min'      => 'Tên đăng nhập phải có ít nhất 3 ký tự.',
            'username.max'      => 'Tên đăng nhập không được vượt quá 30 ký tự.',
            'username.regex'    => 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới.',
            'username.unique'   => 'Tên đăng nhập đã tồn tại.',

            'email.required'    => 'Vui lòng nhập email.',
            'email.email'       => 'Email không đúng định dạng.',
            'email.max'         => 'Email không được vượt quá 255 ký tự.',

            'fullname.required' => 'Vui lòng nhập họ tên.',

            'phone.required'    => 'Vui lòng nhập số điện thoại.',
            'phone.regex'       => 'Số điện thoại không hợp lệ. Phải bắt đầu bằng 03, 05, 07, 08 hoặc 09 và có 10 số.',
            'phone.unique'      => 'Số điện thoại này đã được sử dụng.',

            'address.required'  => 'Vui lòng nhập địa chỉ.',
            'role.required'     => 'Vai trò bắt buộc.',
            'point.required'    => 'Điểm không được để trống.',
            'point.integer'     => 'Điểm phải là số nguyên.',
            'banned.required'   => 'Vui lòng chọn trạng thái.',

            'password.min'      => 'Mật khẩu phải ít nhất 8 ký tự.',
            'password.regex'    => 'Mật khẩu phải chứa ít nhất một chữ hoa, một chữ thường, một số và một ký tự đặc biệt.',
        ]);

        // Không cho đổi email
        if ($request->email !== $user->email) {
            $validator->after(function ($validator) {
                $validator->errors()->add('email', 'Không được thay đổi địa chỉ email.');
            });
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['email'] = $user->email; // ép giữ nguyên email

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
    /**
     * Xem biến động số dư
     */
    public function balanceLog($id)
    {
        $user = User::findOrFail($id);

        $transactions = Transaction::with('user')
            ->where('user_id', $id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.users.balance-log', compact('transactions', 'user'));
    }

    public function activityLog($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $activities = UserActivityLog::where('username', $user->username)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.users.activity-log', compact('activities', 'user'));
    }




    /**
     * Admin cộng/trừ số dư cho user
     */
    public function getClientIp(Request $request)
    {
        $forwarded = $request->header('X-Forwarded-For');
        if ($forwarded) {
            return explode(',', $forwarded)[0]; // IP thực từ proxy
        }

        return $request->ip(); // fallback nếu không có
    }
    public function adjustBalance(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer|min:1',
            'type' => 'required|in:add,subtract',
            'description' => 'nullable|string|max:255',
        ], [
            'amount.required' => 'Vui lòng nhập số tiền.',
            'amount.integer' => 'Số tiền phải là số nguyên.',
            'amount.min' => 'Số tiền tối thiểu là 1.',
            'type.required' => 'Vui lòng chọn loại thao tác.',
            'type.in' => 'Loại thao tác không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($id);
        $amount = $request->amount;
        $type = $request->type;

        $before = $user->balance;
        $after = $type === 'add' ? ($before + $amount) : ($before - $amount);

        if ($after < 0) {
            return response()->json(['error' => 'Không đủ số dư để trừ.'], 422);
        }

        $user->balance = $after;
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'admin_id' => auth()->id(),
            'amount' => $amount,
            'type' => $type,
            'balance_before' => $before,
            'balance_after' => $after,
            'unique_id' => uniqid('manual_'),
            'description' => $request->description ?? ($type === 'add' ? 'Admin cộng tiền' : 'Admin trừ tiền'),
        ]);
        $ip = $this->getClientIp($request); // ✅ Dùng IP thật thay vì $request->ip()
        UserActivityLog::create([
            'username' => $user->username,
            'action' => $type === 'add' ? 'Admin cộng tiền' : 'Admin trừ tiền',
            'ip_address' => $ip,
            'user_agent' => $request->userAgent(),
            'description' => 'Admin #' . auth()->id() . ' ' . ($type === 'add' ? 'cộng' : 'trừ') . " {$amount}đ cho user #{$user->id}",
        ]);

        return response()->json([
            'success' => true,
            'message' => $type === 'add' ? 'Cộng số dư thành công.' : 'Trừ số dư thành công.',
        ]);
    }
}
