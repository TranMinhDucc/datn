@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Thêm mới Menu</h2>

  <form method="POST" action="{{ route('admin.menus.store') }}" class="card p-4 shadow">
    @csrf

    <div class="mb-3">
        <label for="title" class="form-label">Tên:</label>
        <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
    </div>

    <div class="mb-3">
        <label for="url" class="form-label">Url:</label>
        <input type="text" id="url" name="url" class="form-control" value="{{ old('url') }}">
    </div>

    <div class="mb-3">
        <label for="position" class="form-label">Kiểu:</label>
        <select id="position" name="position" class="form-select">
            <option value="">-- Chọn kiểu hiển thị --</option>
            <option value="header" @selected(old('position') == 'header')>Header</option>
            <option value="footer" @selected(old('position') == 'footer')>Footer</option>
            <option value="sidebar" @selected(old('position') == 'sidebar')>Sidebar</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="parent_id" class="form-label">Menu cha:</label>
        <select id="parent_id" name="parent_id" class="form-select">
            <option value="">-- Không có (Menu gốc) --</option>
            @foreach ($allMenus as $menu)
                <option value="{{ $menu->id }}" @selected(old('parent_id') == $menu->id)>
                    {{ $menu->title }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="sort_order" class="form-label">Sắp xếp:</label>
        <input type="number" id="sort_order" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
    </div>

    <div class="mb-3">
        <label for="language" class="form-label">Ngôn ngữ:</label>
        <select id="language" name="language" class="form-select">
            <option value="vi" @selected(old('language') == 'vi')>Tiếng Việt</option>
            <option value="en" @selected(old('language') == 'en')>English</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="active" class="form-label">Trạng thái:</label>
        <select id="active" name="active" class="form-select">
            <option value="1" @selected(old('active', 1) == 1)>Hoạt động</option>
            <option value="0" @selected(old('active') == 0)>Không hoạt động</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Lưu menu</button>
</form>

</div>
@endsection
