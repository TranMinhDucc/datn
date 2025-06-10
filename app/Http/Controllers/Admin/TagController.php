<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('products')->get();
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('tags', 'name')],
        ], [
            'name.required' => 'Vui lòng nhập tên tag.',
            'name.string' => 'Tên tag phải là chuỗi.',
            'name.max' => 'Tên tag không được dài quá 50 ký tự.',
            'name.unique' => 'Tên tag đã tồn tại.',
        ]);

        // Nếu có logic bật/tắt trạng thái, bạn có thể xử lý ở đây
        // $data['status'] = $request->has('status') ? 1 : 0;

        // Tạo slug từ name
        $data['slug'] = Str::slug($data['name']);

        Tag::create($data);

        return redirect()->route('admin.tags.index')->with('success', 'Tag được tạo thành công!');
    }
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tags', 'name')->ignore($tag->id),
            ],
        ], [
            'name.required' => 'Vui lòng nhập tên tag.',
            'name.string' => 'Tên tag phải là chuỗi.',
            'name.max' => 'Tên tag không được dài quá 50 ký tự.',
            'name.unique' => 'Tên tag đã tồn tại.',
        ]);

        $data['slug'] = \Str::slug($data['name']);

        $tag->update($data);

        return redirect()->route('admin.tags.index')->with('success', 'Tag đã được cập nhật!');
    }


    public function destroy(Tag $tag)
    {
        $tag->delete();
        return back()->with('success', 'Tag đã bị xoá!');
    }
}
