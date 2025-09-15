<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
  public function index()
{
    $banners = Banner::orderBy('created_at', 'desc')->paginate(5); // 10 banners mỗi trang
    return view('admin.banners.index', compact('banners'));
}

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subtitle' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'sub_image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'sub_image_1_name' => 'nullable|string',
            // 'sub_image_1_price' => 'nullable|numeric',
            // 'sub_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'sub_image_2_name' => 'nullable|string',
            // 'sub_image_2_price' => 'nullable|numeric',
            'status' => 'nullable|boolean',
        ]);

        // Lưu ảnh nếu có
        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('banners', 'public');
        }
        if ($request->hasFile('sub_image_1')) {
            $data['sub_image_1'] = $request->file('sub_image_1')->store('banners', 'public');
        }
        if ($request->hasFile('sub_image_2')) {
            $data['sub_image_2'] = $request->file('sub_image_2')->store('banners', 'public');
        }
            $data['status'] = $request->has('status') ? 1 : 0; // ✅ Dòng thêm vào


        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Tạo banner thành công!');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'subtitle' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'sub_image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'sub_image_1_name' => 'nullable|string',
            // 'sub_image_1_price' => 'nullable|numeric',
            // 'sub_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'sub_image_2_name' => 'nullable|string',
            // 'sub_image_2_price' => 'nullable|numeric',
            'status' => 'nullable|boolean',
        ]);

        // Cập nhật ảnh nếu có upload mới
        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('banners', 'public');
        }

        if ($request->hasFile('sub_image_1')) {
            $data['sub_image_1'] = $request->file('sub_image_1')->store('banners', 'public');
        }

        if ($request->hasFile('sub_image_2')) {
            $data['sub_image_2'] = $request->file('sub_image_2')->store('banners', 'public');
        }
    $data['status'] = $request->has('status') ? 1 : 0; // ✅ Dòng thêm vào

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Cập nhật banner thành công!');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Xóa banner thành công!');
    }
    public function show(Banner $banner)
    {
        return view('admin.banners.show', compact('banner'));
    }
}
