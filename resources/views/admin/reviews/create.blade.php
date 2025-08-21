@extends('layouts.admin')

@section('title', 'Thêm đánh giá')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3">Thêm đánh giá mới</h1>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-light">
                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card card-flush">
                <div class="card-body py-5">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Đã xảy ra lỗi:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.reviews.store') }}" method="POST">
                        @csrf

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label">Người dùng</label>
                                <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                                    <option value="">-- Chọn người dùng --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->username }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Sản phẩm</label>
                                <select name="product_id" class="form-select @error('product_id') is-invalid @enderror">
                                    <option value="">-- Chọn sản phẩm --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Số sao đánh giá</label>
                            <select name="rating" class="form-select @error('rating') is-invalid @enderror">
                                <option value="">-- Chọn số sao --</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                                @endfor
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Bình luận</label>
                            <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="4" placeholder="Nhập nội dung đánh giá...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Xác minh</label>
                            <select name="verified_purchase" class="form-select @error('verified_purchase') is-invalid @enderror">
                                <option value="1" {{ old('verified_purchase') == '1' ? 'selected' : '' }}>Đã xác minh</option>
                                <option value="0" {{ old('verified_purchase') == '0' ? 'selected' : '' }}>Chưa xác minh</option>
                            </select>
                            @error('verified_purchase')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save me-1"></i> Lưu đánh giá
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
