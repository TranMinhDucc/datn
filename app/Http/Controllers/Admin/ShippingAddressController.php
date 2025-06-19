<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingAddressController extends Controller
{
    public function index()
    {
        $addresses = ShippingAddress::with('user')->latest()->get();
        return view('admin.shipping_addresses.index', compact('addresses'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.shipping_addresses.create', compact('users'));
    }

 public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'title' => 'required|in:Nhà riêng,Công ty,Khác',
        'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
        'pincode' => ['required', 'regex:/^\d{5}$/'],
        'country' => 'required|string|max:100|not_regex:/[<>]/',
        'state' => 'required|string|max:100|not_regex:/[<>]/',
        'city' => 'required|string|max:100|not_regex:/[<>]/',
        'address' => 'required|string|min:10|max:500|not_regex:/[<>]/',
    ], [
        'required' => ':attribute không được để trống.',
        'in' => ':attribute không hợp lệ.',
        'regex' => ':attribute không đúng định dạng.',
        'min' => ':attribute phải có ít nhất :min ký tự.',
        'max' => ':attribute không vượt quá :max ký tự.',
        'string' => ':attribute phải là văn bản.',
        'not_regex' => ':attribute chứa ký tự không hợp lệ.',
    ], [
        'title' => 'Loại địa chỉ',
        'phone' => 'Số điện thoại',
        'pincode' => 'Mã bưu chính',
        'country' => 'Quốc gia',
        'state' => 'Tỉnh/Thành phố',
        'city' => 'Quận/Huyện',
        'address' => 'Địa chỉ',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Lưu vào DB
    ShippingAddress::create([
        'user_id' => auth()->id(),
        'title' => $request->title,
        'phone' => $request->phone,
        'pincode' => $request->pincode,
        'country' => strip_tags($request->country),
        'state' => strip_tags($request->state),
        'city' => strip_tags($request->city),
        'address' => strip_tags($request->address),
    ]);

    return redirect()->back()->with('success', 'Thêm địa chỉ thành công!');
}

    public function edit($id)
    {
        $address = ShippingAddress::findOrFail($id);
        $users = User::all();
        return view('admin.shipping_addresses.edit', compact('address', 'users'));
    }

   public function update(Request $request, $id)
{
    $address = ShippingAddress::findOrFail($id);

    $request->validate([
        'user_id' => 'required|exists:users,id',
        'title' => 'nullable|string|max:100',
        'address' => 'required|string',
        'country' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'city' => 'nullable|string|max:100',
        'pincode' => 'nullable|string|max:20',
        'phone' => 'nullable|string|max:20',
        'is_default' => 'nullable|boolean',
        'status' => 'required|in:0,1',
    ], [
        'user_id.required' => 'Vui lòng chọn người dùng.',
        'user_id.exists' => 'Người dùng không tồn tại.',
        'address.required' => 'Vui lòng nhập địa chỉ.',
        'title.max' => 'Tên tiêu đề không được vượt quá 100 ký tự.',
        'country.max' => 'Tên quốc gia không được vượt quá 100 ký tự.',
        'state.max' => 'Tên tỉnh/thành không được vượt quá 100 ký tự.',
        'city.max' => 'Tên thành phố không được vượt quá 100 ký tự.',
        'pincode.max' => 'Mã bưu điện không được vượt quá 20 ký tự.',
        'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
        'status.required' => 'Vui lòng chọn trạng thái.',
        'status.in' => 'Trạng thái không hợp lệ.',
    ]);

    $request->merge(['is_default' => $request->has('is_default')]);

    if ($request->is_default) {
        ShippingAddress::where('user_id', $request->user_id)->update(['is_default' => false]);
    }

    $address->update($request->all());

    return redirect()->route('admin.shipping-addresses.index')->with('success', 'Cập nhật địa chỉ thành công!');
}

    public function destroy($id)
    {
        $address = ShippingAddress::findOrFail($id);
        $address->delete();

        return back()->with('success', 'Xóa địa chỉ thành công!');
    }
}
