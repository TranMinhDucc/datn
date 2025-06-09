@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="card p-4">
        <div class="card-header">
            <h3 class="text-center">Chi tiết danh mục</h3>
        </div>
        <div class="card-body pt-0">
            <div class="mb-10">

                {{-- Tên danh mục --}}
                <div class="d-flex mb-4">
                    <div class="fw-bold me-3" style="width: 200px;">Tên danh mục:</div>
                    <div>{{ $category->name }}</div>
                </div>

                {{-- Danh mục cha --}}
                <div class="d-flex mb-4">
                    <div class="fw-bold me-3" style="width: 200px;">Danh mục cha:</div>
                    <div>{{ $category->parent->name ?? 'Không có' }}</div>
                </div>

                {{-- Mô tả --}}
                <div class="d-flex mb-4">
                    <div class="fw-bold me-3" style="width: 200px;">Mô tả:</div>
                    <div>{{ $category->description ?? 'Không có' }}</div>
                </div>

                {{-- Ngày tạo --}}
                <div class="d-flex mb-4">
                    <div class="fw-bold me-3" style="width: 200px;">Ngày tạo:</div>
                    <div>{{ $category->created_at->format('d/m/Y H:i') }}</div>
                </div>

                {{-- Cập nhật --}}
                <div class="d-flex mb-4">
                    <div class="fw-bold me-3" style="width: 200px;">Cập nhật:</div>
                    <div>{{ $category->updated_at->format('d/m/Y H:i') }}</div>
                </div>

            </div>

            <!-- Nút hành động -->
            <div class="text-center mt-4">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary">Chỉnh sửa</a>
            </div>
        </div>
    </div>
</div>
@endsection
