@extends('layouts.admin')

@section('title', 'Thêm nhãn dán')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 my-0">Thêm nhãn dán sản phẩm</h1>
            <a href="{{ route('admin.product-labels.index') }}" class="btn btn-light-primary">Quay lại</a>
        </div>
    </div>

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div class="app-container container-xxl">
            <div class="card">
                <div class="card-body p-10">
                    <form action="{{ route('admin.product-labels.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Sản phẩm</label>
                            <select name="product_id" class="form-select" required>
                                <option value="">-- Chọn sản phẩm --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Hình ảnh nhãn</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Vị trí</label>
                            <input type="text" name="position" class="form-control" placeholder="VD: top-left, bottom-right" required>
                        </div>

                        <button type="submit" class="btn btn-danger">Thêm nhãn</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection