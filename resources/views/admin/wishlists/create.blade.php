@extends('layouts.admin')

@section('title', 'Thêm sản phẩm yêu thích')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div class="app-toolbar py-3 py-lg-6">
        <div class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading text-gray-900 fw-bold fs-3">Thêm sản phẩm yêu thích</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.wishlists.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
            </div>
        </div>
    </div>

    <div class="app-content flex-column-fluid">
        <div class="app-container container-xxl">
            <div class="card card-flush">
                <div class="card-body">
                    <form action="{{ route('admin.wishlists.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Người dùng</label>
                            <select name="user_id" class="form-select" required>
                                <option value="">-- Chọn người dùng --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->fullname }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sản phẩm</label>
                            <select name="product_id" class="form-select" required>
                                <option value="">-- Chọn sản phẩm --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            @error('product_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="is_active" class="form-select">
                                <option value="1" selected>Hiện</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Ghi chú tuỳ chọn..."></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-danger">Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
