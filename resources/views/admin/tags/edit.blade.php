@extends('layouts.admin')
@section('title', 'Chỉnh sửa Tag')
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Chỉnh sửa Tag</h3>
                    </div>

                    <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            {{-- Hiển thị lỗi --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Đã có lỗi xảy ra:</strong>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Tên Tag --}}
                            <div class="mb-3">
                                <label class="form-label">Tên Tag</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $tag->name) }}" placeholder="Nhập tên tag..." required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Slug --}}
                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug"
                                    class="form-control @error('slug') is-invalid @enderror"
                                    value="{{ old('slug', $tag->slug) }}" placeholder="Slug sẽ tự tạo nếu để trống...">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Mô tả --}}
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Nhập mô tả ngắn...">{{ old('description', $tag->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Trạng thái --}}
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="is_active"
                                    class="form-select @error('is_active') is-invalid @enderror">
                                    <option value="1" {{ old('is_active', $tag->is_active) == 1 ? 'selected' : '' }}>Kích hoạt</option>
                                    <option value="0" {{ old('is_active', $tag->is_active) == 0 ? 'selected' : '' }}>Ẩn</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Thứ tự sắp xếp --}}
                            <div class="mb-3">
                                <label class="form-label">Thứ tự sắp xếp</label>
                                <input type="number" name="sort_order"
                                    class="form-control @error('sort_order') is-invalid @enderror"
                                    value="{{ old('sort_order', $tag->sort_order) }}" min="1">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('admin.tags.index') }}" class="btn btn-light">Huỷ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
