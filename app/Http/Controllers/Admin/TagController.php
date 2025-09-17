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
        $maxOrder = Tag::max('sort_order') ?? 0;

        $data = $request->validate([
            'name'        => 'required|string|max:50|unique:tags,name,' . ($tag->id ?? 'NULL'),
            'slug'        => 'required|string|max:100|unique:tags,slug,' . ($tag->id ?? 'NULL'),
            'description' => 'nullable|string|max:255',
            'is_active'   => 'boolean',
            'sort_order'  => [
                'required',
                'integer',
                'min:1',
                'max:' . (\App\Models\Tag::count() + 1),
                Rule::unique('tags', 'sort_order')->ignore($tag->id ?? null), // 🔥 không cho trùng
            ],
        ], [
            'sort_order.required' => 'Thứ tự sắp xếp là bắt buộc.',
            'sort_order.min'      => 'Thứ tự sắp xếp phải lớn hơn hoặc bằng 1.',
            'sort_order.max'      => 'Thứ tự sắp xếp không được lớn hơn :max.',
            'sort_order.unique'   => 'Thứ tự sắp xếp này đã được sử dụng, vui lòng chọn số khác.',
        ]);



        if (empty($data['sort_order'])) {
            $data['sort_order'] = $maxOrder + 1;
        }

        Tag::create($data);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tạo tag thành công!');
    }



    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
{
    $data = $request->validate([
        'name'        => 'required|string|max:50|unique:tags,name,' . $tag->id,
        'slug'        => 'required|string|max:100|unique:tags,slug,' . $tag->id,
        'description' => 'nullable|string|max:255',
        'is_active'   => 'boolean',
        'sort_order'  => 'nullable|integer|min:1',
    ]);

    $oldOrder = $tag->sort_order;
    $newOrder = $data['sort_order'] ?? $oldOrder;

    if ($newOrder != $oldOrder) {
        if ($newOrder < $oldOrder) {
            // Dời các tag từ newOrder → oldOrder-1 xuống +1
            Tag::whereBetween('sort_order', [$newOrder, $oldOrder - 1])
                ->increment('sort_order');
        } else {
            // Dời các tag từ oldOrder+1 → newOrder lên -1
            Tag::whereBetween('sort_order', [$oldOrder + 1, $newOrder])
                ->decrement('sort_order');
        }
    }

    $tag->update($data);

    return redirect()->route('admin.tags.index')->with('success', 'Cập nhật tag thành công!');
}


    protected function reorderTags()
    {
        $tags = Tag::orderBy('sort_order')->get();
        $i = 1;
        foreach ($tags as $tag) {
            $tag->updateQuietly(['sort_order' => $i++]);
        }
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return back()->with('success', 'Tag đã bị xoá!');
    }
}
