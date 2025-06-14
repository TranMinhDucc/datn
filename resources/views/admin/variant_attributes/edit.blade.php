@extends('layouts.admin')
@section('title', 'Chỉnh sửa thuộc tính')
@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-header bg-light">
            <h3 class="card-title">Chỉnh sửa thuộc tính</h3>
        </div>
        <form action="{{ route('admin.variant_attributes.update', $attribute->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <!-- Tên thuộc tính -->
                <div class="mb-4">
                    <label for="name" class="form-label">Tên thuộc tính</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $attribute->name) }}" required>
                </div>

                <!-- Giá trị -->
                <div class="mb-4">
                    <label for="values" class="form-label">Giá trị (cách nhau bằng dấu |)</label>
                    <input type="text" class="form-control" id="values" name="values"
                           value="{{ old('values', $attribute->values->pluck('value')->implode('|')) }}">
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end gap-2">
                <a href="{{ route('admin.variant_attributes.index') }}" class="btn btn-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endsection
