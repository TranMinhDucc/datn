@extends('layouts.admin')

@section('title', 'Cập nhật thương hiệu')

@section('content')
<div class="container py-4" style="max-width: 650px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-white d-flex justify-content-center align-items-center py-5 rounded-top">
            <h4 class="mb-0 fw-bold text-uppercase">Cập nhật Thương hiệu</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Tên thương hiệu <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', $brand->name) }}"
                        class="form-control @error('name') is-invalid @enderror"
                        required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Logo hiện tại</label><br>
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}"
                             alt="{{ $brand->name }}"
                             class="img-fluid rounded shadow-sm border mb-2"
                             style="max-height: 100px;">
                    @else
                        <p class="text-muted fst-italic">Chưa có logo</p>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="logo" class="form-label fw-semibold">Thay đổi Logo</label>
                    <input type="file" name="logo" id="logo"
                        class="form-control @error('logo') is-invalid @enderror"
                        accept="image/*">
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ✅ Thêm trường Trạng thái --}}
                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Trạng thái</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="1" {{ old('status', $brand->status) == 1 ? 'selected' : '' }}>Công bố</option>
                        <option value="0" {{ old('status', $brand->status) == 0 ? 'selected' : '' }}>Chưa công bố</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
