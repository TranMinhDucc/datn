@extends('layouts.admin')

@section('content')

<h1>Danh sách Menu</h1>
<a href="{{ route('admin.menus.create') }}" class="btn btn-primary mb-3">+ Thêm Menu</a>

@php
    if (!function_exists('renderRow')) {
        function renderRow($menu, $prefix = '') {
            echo '<tr>';
            echo '<td>' . $menu->id . '</td>';
            echo '<td>' . $prefix . e($menu->title) . '</td>';
            echo '<td>' . e($menu->url) . '</td>';
            echo '<td>' . e($menu->position) . '</td>';
            echo '<td>' . e($menu->language) . '</td>';
            echo '<td><input type="checkbox" class="toggle-active" data-id="' . $menu->id . '" ' . ($menu->active ? 'checked' : '') . '></td>';
            echo '<td>' . ($menu->created_at ? $menu->created_at->format("Y-m-d H:i:s") : '') . '</td>';
            echo '<td>';
            echo '<a href="' . route('admin.menus.edit', $menu) . '" class="btn btn-sm btn-info">✏️</a> ';
            echo '<form action="' . route('admin.menus.destroy', $menu) . '" method="POST" style="display:inline">';
            echo csrf_field() . method_field('DELETE');
            echo '<button onclick="return confirm(\'Bạn chắc chắn muốn xoá?\')" class="btn btn-sm btn-danger">🗑️</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';

            foreach ($menu->children as $child) {
                renderRow($child, $prefix . '');
            }
        }
    }
@endphp

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>URL</th>
            <th>Vị trí</th>
            <th>Ngôn ngữ</th>
            <th>Hiển thị</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($menus as $menu)
            @php renderRow($menu); @endphp
        @endforeach
    </tbody>
</table>

<script>
    document.querySelectorAll('.toggle-active').forEach(function (el) {
        el.addEventListener('change', function () {
            const id = this.dataset.id;
            const active = this.checked ? 1 : 0;

            fetch(`/admin/menus/${id}/toggle-active`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ active })
            });
        });
    });
</script>

@endsection

