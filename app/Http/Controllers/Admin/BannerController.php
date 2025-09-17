<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::query()
            ->with([
                'product1:id,name,image',
                'product2:id,name,image',
            ])
            ->latest()
            ->paginate(10);

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        $products = Product::select('id', 'name', 'image')->get()->map(function ($p) {
            $p->image_url = $p->image
                ? (str_starts_with($p->image, 'http') ? $p->image : asset('storage/' . $p->image))
                : asset('images/no-image.png');
            return $p;
        });
        return view('admin.banners.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subtitle' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id_1'  => ['nullable', 'exists:products,id'],
            'product_id_2'  => ['nullable', 'exists:products,id'],
            'btn_title'     => ['nullable', 'string', 'max:255'],
            'btn_link'      => ['nullable', 'url', 'max:2048'],
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
        // if ($request->hasFile('sub_image_1')) {
        //     $data['sub_image_1'] = $request->file('sub_image_1')->store('banners', 'public');
        // }
        // if ($request->hasFile('sub_image_2')) {
        //     $data['sub_image_2'] = $request->file('sub_image_2')->store('banners', 'public');
        // }
        $data['status'] = $request->has('status') ? 1 : 0; // ✅ Dòng thêm vào
        $data['btn_title'] = $data['btn_title'] ?? 'Shop Now';
        $data['btn_link']  = $data['btn_link']  ?? route('client.category.index');

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Tạo banner thành công!');
    }

    public function edit(Banner $banner)
    {
        $products = Product::query()
            ->select('id', 'name', 'image') // Đổi cho đúng schema của bạn
            ->get()
            ->map(function ($p) {
                $p->image_url = $p->image
                    ? (Str::startsWith($p->image, ['http://', 'https://']) ? $p->image : asset('storage/' . $p->image))
                    : asset('images/no-image.png');
                return $p;
            });

        // Eager load để hiện preview nếu cần
        $banner->load(['product1:id,name,image', 'product2:id,name,image']);

        return view('admin.banners.edit', compact('banner', 'products'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'subtitle' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id_1' => ['nullable', 'exists:products,id'],
            'product_id_2' => ['nullable', 'exists:products,id'],
            'btn_title'    => ['nullable', 'string', 'max:255'],
            'btn_link'     => ['nullable', 'url', 'max:2048'], // dùng 'url' nếu chỉ chấp nhận URL đầy đủ

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

        // if ($request->hasFile('sub_image_1')) {
        //     $data['sub_image_1'] = $request->file('sub_image_1')->store('banners', 'public');
        // }

        // if ($request->hasFile('sub_image_2')) {
        //     $data['sub_image_2'] = $request->file('sub_image_2')->store('banners', 'public');
        // }
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
