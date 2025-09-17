@extends('layouts.admin') {{-- Layout Metronic --}}
@section('title', 'Tag')
@section('content')
<div class="d-flex flex-column flex-column-fluid">

    <div id="kt_app_content" class="app-content  flex-column-fluid ">
        <div id="kt_app_content_container" class="app-container  container-xxl ">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <form method="GET" action="{{ route('admin.search') }}">
                            <input type="hidden" name="module" value="tags">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="fa fa-search fs-4 position-absolute ms-4"></i>
                                <input type="text" name="keyword" value="{{ request('keyword') }}"
                                    class="form-control form-control-solid w-250px ps-12"
                                    placeholder="Tìm kiếm thẻ (tag)" />
                            </div>
                        </form>
                    </div>

                    <div class="card-toolbar">
                        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">Thêm Tag</a>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th>ID</th>
                                    <th>Tên Tag</th>
                                    <th>Slug</th>
                                    <th>Số sản phẩm</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($tags as $tag)
                                <tr>
                                    <td>{{ $tag->id }}</td>
                                    <td>{{ $tag->name }}</td>
                                    <td>{{ $tag->slug }}</td>
                                    <td>{{ $tag->products_count }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-active-light-primary dropdown-toggle"
                                                type="button"
                                                id="dropdownMenuButton{{ $tag->id }}"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $tag->id }}">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.tags.edit', $tag->id) }}">Edit</a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.tags.destroy', $tag->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa tag này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection