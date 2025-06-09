@extends('layouts.admin')

@section('title', 'Thêm thương hiệu')

@section('content')
<div class="container py-4" style="max-width: 650px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-center align-items-center py-5 rounded-top">
            <h2 class="mb-0 fw-bold text-uppercase">Thêm thương hiệu mới</h2>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- Tên thương hiệu --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Tên thương hiệu <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                        required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Logo --}}
                <div class="mb-3">
                    <label for="logo" class="form-label fw-semibold">Logo</label>
                    <input type="file" name="logo" id="logo"
                        class="form-control @error('logo') is-invalid @enderror"
                        accept="image/*">
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Trạng thái --}}
                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                    <select name="status" id="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required>
                        <option value="">-- Chọn trạng thái --</option>
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Công bố</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Chưa công bố</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
