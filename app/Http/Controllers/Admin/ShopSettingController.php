<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopSetting;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Http\Request;

class ShopSettingController extends Controller
{
    public function edit()
    {
        $setting = ShopSetting::first(); // lấy bản ghi đầu tiên
        $provinces = Province::all();

        // ✅ Load districts & wards nếu có tỉnh/huyện được chọn
        $districts = collect();
        $wards = collect();

        if ($setting && $setting->province_id) {
            $districts = District::where('province_id', $setting->province_id)->get();
        }

        if ($setting && $setting->district_id) {
            $wards = Ward::where('district_id', $setting->district_id)->get();
        }

        return view('admin.shopSettings.edit', compact('setting', 'provinces', 'districts', 'wards'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'shop_name'    => 'required|string|max:255',
            'shop_phone'   => 'required|string|max:20',
            'address'      => 'required|string|max:255',
            'province_id'  => 'required|integer|exists:provinces,id',
            'district_id'  => 'required|integer|exists:districts,id',
            'ward_id'      => 'required|integer|exists:wards,id',
        ]);

        // ✅ Update nếu tồn tại, ngược lại tạo mới (id = 1)
        ShopSetting::updateOrCreate(['id' => 1], $data);

        return redirect()->back()->with('success', 'Cập nhật thông tin cửa hàng thành công!');
    }
}
