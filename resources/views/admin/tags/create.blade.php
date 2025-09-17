@extends('layouts.admin')
@section('title', 'Tạo Tag mới')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tạo Tag mới</h3>
                </div>

                <form action="{{ route('admin.tags.store') }}" method="POST">
                    @csrf

                    <div class="card-body">

                        {{-- Tên Tag --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên Tag</label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Nhập tên tag...">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="mb-3">
    <label for="slug" class="form-label">Slug</label>
    <input type="text" id="slug" name="slug"
        class="form-control @error('slug') is-invalid @enderror"
        value="{{ old('slug') }}" placeholder="Slug sẽ tự tạo khi nhập tên...">
    @error('slug')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


                        {{-- Mô tả --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea id="description" name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                rows="3"
                                placeholder="Mô tả ngắn cho tag...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Trạng thái --}}
                        <div class="mb-3">
                            <label for="is_active" class="form-label">Trạng thái</label>
                            <select name="is_active" id="is_active"
                                class="form-select @error('is_active') is-invalid @enderror">
                                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Thứ tự sắp xếp --}}
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Thứ tự sắp xếp</label>
                            <input type="number" id="sort_order" name="sort_order"
                                class="form-control @error('sort_order') is-invalid @enderror"
                                value="{{ old('sort_order', 0) }}"
                                placeholder="VD: 1, 2, 3...">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                        <a href="{{ route('admin.tags.index') }}" class="btn btn-light">Huỷ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function slugify(str) {
        return str.toString().toLowerCase()
            .normalize('NFD')                   // xoá dấu tiếng Việt
            .replace(/[\u0300-\u036f]/g, '')    // xoá ký tự tổ hợp
            .replace(/[^a-z0-9\s-]/g, '')       // bỏ ký tự đặc biệt
            .trim()                             // bỏ khoảng trắng đầu/cuối
            .replace(/\s+/g, '-')               // thay space bằng -
            .replace(/-+/g, '-');               // gộp nhiều - thành 1
    }

    document.getElementById('name').addEventListener('input', function() {
        let slugInput = document.getElementById('slug');
        if (!slugInput.dataset.touched) { // chỉ auto khi user chưa sửa slug
            slugInput.value = slugify(this.value);
        }
    });

    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.touched = true; // user gõ tay thì ko auto nữa
    });
</script>

@endsection
