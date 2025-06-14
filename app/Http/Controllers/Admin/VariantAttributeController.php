<?php

namespace App\Http\Controllers\Admin;

use App\Models\VariantAttribute;
use App\Models\VariantValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VariantAttributeController extends Controller
{
    public function index()
    {
        $attributes = VariantAttribute::with('values')->paginate(10);
        return view('admin.variant_attributes.index', compact('attributes'));
    }
    public function create()
    {
        return view('admin.variant_attributes.create');
    }

    public function store(Request $request)
    {
        $attributes = $request->input('attributes');

        if (!$attributes || !is_array($attributes)) {
            return back()->withErrors(['attributes' => 'Bạn phải nhập ít nhất 1 thuộc tính.'])->withInput();
        }

        foreach ($attributes as $index => $item) {
            if (empty($item['name']) || empty($item['values'])) {
                return back()->withErrors([
                    "attributes.$index" => "Thuộc tính và giá trị không được để trống."
                ])->withInput();
            }

            // ✅ Kiểm tra trùng
            $existing = VariantAttribute::where('name', $item['name'])->first();
            if ($existing) {
                return back()->withErrors([
                    "attributes.$index.name" => "Tên thuộc tính '{$item['name']}' đã tồn tại."
                ])->withInput();
            }

            // ✅ Tạo mới thuộc tính
            $attr = VariantAttribute::create(['name' => $item['name']]);

            // Tách giá trị theo dấu |
            $values = explode('|', $item['values']);
            foreach ($values as $val) {
                $val = trim($val);
                if ($val !== '') {
                    $attr->values()->create(['value' => $val]);
                }
            }
        }

        return redirect()->route('admin.variant_attributes.index')
            ->with('success', '🎉 Thêm thuộc tính và giá trị thành công!');
    }


    public function edit($id)
    {
        $attribute = VariantAttribute::with('values')->findOrFail($id);
        return view('admin.variant_attributes.edit', compact('attribute'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:variant_attributes,name,' . $id,
            'values' => 'nullable|string'
        ]);

        $attribute = VariantAttribute::findOrFail($id);
        $attribute->name = $request->name;
        $attribute->save();

        // Cập nhật giá trị
        $values = array_filter(array_map('trim', explode('|', $request->values)));

        // Xoá toàn bộ giá trị cũ
        $attribute->values()->delete();

        // Thêm lại
        foreach ($values as $val) {
            $attribute->values()->create(['value' => $val]);
        }

        return redirect()->route('admin.variant_attributes.index')
            ->with('success', '🎉 Cập nhật thuộc tính thành công!');
    }
    public function destroy($id)
    {
        $attribute = VariantAttribute::with('values')->findOrFail($id);

        // Xoá các giá trị con trước
        $attribute->values()->delete();

        // Xoá chính thuộc tính
        $attribute->delete();

        return redirect()->route('admin.variant_attributes.index')
            ->with('success', '🗑️ Thuộc tính đã được xóa thành công!');
    }
}
