@extends('layouts.admin') {{-- Layout Metronic --}}
@section('title', 'Tag')
@section('content')
    <div class="d-flex flex-column flex-column-fluid">

        <div id="kt_app_content" class="app-content  flex-column-fluid ">
            <div id="kt_app_content_container" class="app-container  container-xxl ">
                <div class="card card-flush">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">

                        </div>

                        <div class="card-toolbar">
                            <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">Add Tag</a>
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
                                                <button class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                    Actions <i class="fa fa-chevron-down ms-1"></i>
                                                </button>
                                                <div class="menu menu-sub menu-sub-dropdown w-125px" data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('admin.tags.edit', $tag->id) }}"
                                                            class="menu-link px-3">Edit</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST"
                                                            onsubmit="return confirm('Delete this tag?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="menu-link px-3 btn btn-link p-0 text-start">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
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