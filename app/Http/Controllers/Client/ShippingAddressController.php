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
        return view('client.address.index', compact('addresses'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'title' => 'required|in:Nhà riêng,Công ty,Khác',
            'phone' => [
                'required',
                'regex:/^0[0-9]{9}$/'
            ],
            'pincode' => [
                'required',
                'digits:5'
            ],
            'country' => [
                'required',
                'string',
                'max:100',
                'not_regex:/[<>]/'
            ],
            'address' => [
                'required',
                'string',
                'min:10',
                'max:500',
                'not_regex:/[<>]/'
            ],
            'province_id' => 'nullable|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
        ], [
            'required'   => ':attribute không được để trống.',
            'in'         => ':attribute không hợp lệ.',
            'regex'      => ':attribute không đúng định dạng.',
            'digits'     => ':attribute phải gồm đúng :digits chữ số.',
            'min'        => ':attribute phải có ít nhất :min ký tự.',
            'max'        => ':attribute không vượt quá :max ký tự.',
            'string'     => ':attribute phải là văn bản.',
            'not_regex'  => ':attribute chứa ký tự không hợp lệ.',
            'exists'     => ':attribute không tồn tại trong hệ thống.',
        ], [
            'title'       => 'Loại địa chỉ',
            'phone'       => 'Số điện thoại',
            'pincode'     => 'Mã bưu chính',
            'country'     => 'Quốc gia',
            'state'       => 'Tỉnh/Thành phố',
            'city'        => 'Quận/Huyện',
            'address'     => 'Địa chỉ',
            'province_id' => 'Tỉnh/Thành phố',
            'district_id' => 'Quận/Huyện',
            'ward_id'     => 'Phường/Xã',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('show_add_address_modal', true);
        }

        ShippingAddress::create([
            'user_id'     => auth()->id(),
            'full_name'   => $request->full_name,
            'title'       => $request->title,
            'phone'       => $request->phone,
            'pincode'     => $request->pincode,
            'country'     => $request->country, // Nếu dùng cố định
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'ward_id'     => $request->ward_id,
            'address'     => strip_tags($request->address),
        ]);


        return redirect()->back()->with('success', '✅ Thêm địa chỉ thành công!');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|in:Nhà riêng,Công ty,Khác',
            'phone' => [
                'required',
                'regex:/^0[0-9]{9}$/'
            ],
            'pincode' => [
                'required',
                'digits:5'
            ],
            'country' => [
                'required',
                'string',
                'max:100',
                'not_regex:/[<>]/'
            ],
            'state' => [
                'required',
                'string',
                'max:100',
                'not_regex:/[<>]/'
            ],
            'city' => [
                'required',
                'string',
                'max:100',
                'not_regex:/[<>]/'
            ],
            'address' => [
                'required',
                'string',
                'min:10',
                'max:500',
                'not_regex:/[<>]/'
            ],
        ], [
            'required'   => ':attribute không được để trống.',
            'in'         => ':attribute không hợp lệ.',
            'regex'      => ':attribute không đúng định dạng.',
            'digits'     => ':attribute phải gồm đúng :digits chữ số.',
            'min'        => ':attribute phải có ít nhất :min ký tự.',
            'max'        => ':attribute không vượt quá :max ký tự.',
            'string'     => ':attribute phải là văn bản.',
            'not_regex'  => ':attribute chứa ký tự không hợp lệ.',
        ], [
            'title'   => 'Loại địa chỉ',
            'phone'   => 'Số điện thoại',
            'pincode' => 'Mã bưu chính',
            'country' => 'Quốc gia',
            'state'   => 'Tỉnh/Thành phố',
            'city'    => 'Quận/Huyện',
            'address' => 'Địa chỉ',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('show_edit_address_modal_' . $id, true); // Giúp mở lại modal sửa đúng ID
        }

        $address = ShippingAddress::where('user_id', auth()->id())->findOrFail($id);
        $address->update([
            'title'   => $request->title,
            'phone'   => $request->phone,
            'pincode' => $request->pincode,
            'country' => strip_tags($request->country),
            'state'   => strip_tags($request->state),
            'city'    => strip_tags($request->city),
            'address' => strip_tags($request->address),
        ]);

        return redirect()->back()->with('success', 'Cập nhật địa chỉ thành công!');
    }

    public function destroy($id)
    {
        $address = ShippingAddress::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $address->delete();

        return back()->with('success', 'Đã xoá địa chỉ!');
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
