<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
     public function index()
    {
        $menus = Menu::whereNull('parent_id')->orderBy('sort_order')->with('children')->get();
        return view('admin.menus.index', compact('menus'));
    }

public function create()
{
    $allMenus = Menu::whereNull('parent_id')->get(); // chỉ lấy menu cha cũng được
    return view('admin.menus.create', compact('allMenus'));
}

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255|unique:menus,url',
        ]);

        Menu::create($request->all());

        return redirect()->route('admin.menus.index')->with('success', 'Đã thêm menu');
    }
public function edit(Menu $menu)
{
    $allMenus = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->get(); // đổi tên từ $parents → $allMenus
    return view('admin.menus.edit', compact('menu', 'allMenus'));
}


    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255|unique:menus,url,' . $menu->id,
        ]);

        $menu->update($request->all());

        return redirect()->route('admin.menus.index')->with('success', 'Cập nhật menu thành công');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Xoá menu thành công');
    }

    public function toggleActive(Menu $menu, Request $request)
    {
        $menu->update(['active' => $request->active]);
        return response()->json(['status' => 'ok']);
    }
}
