@extends('layouts.admin')

@section('title', 'Sửa nhãn dán')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!-- Toolbar -->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 my-0">Sửa nhãn dán sản phẩm</h1>
                <a href="{{ route('admin.product-labels.index') }}" class="btn btn-light-primary">Quay lại</a>
            </div>
        </div>

        <!-- Content -->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div class="app-container container-xxl">
                <div class="card">
                    <div class="card-body p-10">
                        <form action="{{ route('admin.product-labels.update', $label->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf @method('PUT')

                            <!-- Chọn nhiều sản phẩm -->
                            <div class="mb-4">
                                <label class="form-label">Sản phẩm áp dụng</label>
                                <select name="products[]" class="form-select select2" multiple required>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ $label->products->contains($product->id) ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Bạn có thể tìm kiếm và chọn nhiều sản phẩm</small>
                            </div>

                            <!-- Ảnh hiện tại + upload -->
                            <div class="mb-4">
                                <label class="form-label">Hình ảnh hiện tại</label><br>
                                <img src="{{ asset($label->image) }}" width="150" class="mb-2 rounded shadow-sm"
                                    alt="label image">
                                <input type="file" name="image" class="form-control mt-2">
                                <small class="text-muted">Chọn ảnh mới nếu muốn thay đổi</small>
                            </div>

                            <!-- Vị trí -->
                            <div class="mb-4">
                                <label class="form-label">Vị trí hiển thị</label>
                                <select name="position" class="form-select" required>
                                    <option value="top-left" {{ $label->position == 'top-left' ? 'selected' : '' }}>Top Left
                                    </option>
                                    <option value="top-right" {{ $label->position == 'top-right' ? 'selected' : '' }}>Top
                                        Right</option>
                                    <option value="bottom-left" {{ $label->position == 'bottom-left' ? 'selected' : '' }}>
                                        Bottom Left</option>
                                    <option value="bottom-right" {{ $label->position == 'bottom-right' ? 'selected' : '' }}>
                                        Bottom Right</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-warning">Cập nhật nhãn</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Chọn sản phẩm",
                allowClear: true
            });
        });
    </script>
@endpush

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
