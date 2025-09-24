<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Validator;

class ShippingAddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->shippingAddresses()->latest()->get();
        return view('admin.shipping_addresses.index', compact('addresses'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|in:Nhà riêng,Công ty,Khác',
            'full_name'   => 'required|string|max:255',
            'phone'       => ['required', 'regex:/^0[0-9]{9}$/'],
            'pincode'     => ['required', 'digits:5'],
            'country'     => 'required|string|max:100|not_regex:/[<>]/',
            'address'     => 'required|string|min:10|max:500|not_regex:/[<>]/',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'ward_id'     => 'required|exists:wards,id',
        ], [
            'required' => ':attribute không được để trống.',
            'exists'   => ':attribute không hợp lệ.',
            'regex'    => ':attribute không đúng định dạng.',
            'digits'   => ':attribute phải gồm đúng :digits chữ số.',
            'min'      => ':attribute phải có ít nhất :min ký tự.',
        ], [
            'title'       => 'Loại địa chỉ',
            'full_name'   => 'Tên người nhận',
            'phone'       => 'Số điện thoại',
            'pincode'     => 'Mã bưu chính',
            'country'     => 'Quốc gia',
            'address'     => 'Địa chỉ',
            'province_id' => 'Tỉnh/Thành phố',
            'district_id' => 'Quận/Huyện',
            'ward_id'     => 'Phường/Xã',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('show_add_address_modal', true);
        }

        ShippingAddress::create([
            'user_id'     => auth()->id(),
            'full_name'   => $request->full_name,
            'title'       => $request->title,
            'phone'       => $request->phone,
            'pincode'     => $request->pincode,
            'country'     => $request->country,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'ward_id'     => $request->ward_id,
            'address'     => strip_tags($request->address),
        ]);

        return back()->with('success', '✅ Thêm địa chỉ thành công!');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|in:Nhà riêng,Công ty,Khác',
            'full_name'   => 'required|string|max:255',
            'phone'       => ['required', 'regex:/^0[0-9]{9}$/'],
            'pincode'     => ['required', 'digits:5'],
            'country'     => 'required|string|max:100|not_regex:/[<>]/',
            'address'     => 'required|string|min:10|max:500|not_regex:/[<>]/',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'ward_id'     => 'required|exists:wards,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with("show_edit_address_modal_$id", true);
        }

        $address = ShippingAddress::where('user_id', auth()->id())->findOrFail($id);
        $address->update([
            'full_name'   => $request->full_name,
            'title'       => $request->title,
            'phone'       => $request->phone,
            'pincode'     => $request->pincode,
            'country'     => $request->country,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'ward_id'     => $request->ward_id,
            'address'     => strip_tags($request->address),
        ]);

        return back()->with('success', 'Cập nhật địa chỉ thành công!');
    }


    public function destroy($id)
    {
        $address = ShippingAddress::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Nếu địa chỉ đã từng có đơn hàng thì chỉ cho phép xóa mềm
        if ($address->orders()->exists()) {
            $address->delete(); // Soft delete (set deleted_at)
            return back()->with('success', 'Địa chỉ đã được xóa thành công');
        }

        // Nếu địa chỉ chưa có đơn hàng thì có thể xóa hẳn
        $address->forceDelete();

        return back()->with('success', 'Địa chỉ của bạn đã được xóa');
    }

    public function setDefault($id)
    {
        $user = auth()->user();

        $address = ShippingAddress::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        ShippingAddress::where('user_id', $user->id)->update(['is_default' => 0]);

        $address->is_default = 1;
        $address->save();

        return back()->with('success', 'Đã cập nhật địa chỉ mặc định!');
    }
}
