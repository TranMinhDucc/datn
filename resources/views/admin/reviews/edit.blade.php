@extends('layouts.admin')

@section('title', 'Chỉnh sửa đánh giá')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3">Chỉnh sửa đánh giá</h1>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-light">
                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card card-flush">
                <div class="card-body py-5">

                    {{-- Thông báo lỗi --}}
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
@php
    $badwords = $badwords ?? [];
    $comment = strtolower($review->comment ?? '');
    $isViolated = false;
    foreach ($badwords as $word) {
        if (str_contains($comment, strtolower($word))) {
            $isViolated = true;
            break;
        }
    }
@endphp

                    <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label">Người dùng</label>
                                <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $review->user_id == $user->id ? 'selected' : '' }}>
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
                                <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ $review->product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-4">
                                <label class="form-label">Đánh giá (sao)</label>
                                <select name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Xác minh</label>
                                <select name="verified_purchase" class="form-select @error('verified_purchase') is-invalid @enderror" required>
                                    <option value="1" {{ $review->verified_purchase ? 'selected' : '' }}>Đã xác minh</option>
                                    <option value="0" {{ !$review->verified_purchase ? 'selected' : '' }}>Chưa xác minh</option>
                                </select>
                                @error('verified_purchase')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Thời gian</label>
                                <input type="datetime-local" name="created_at"
                                    value="{{ \Carbon\Carbon::parse($review->created_at)->format('Y-m-d\TH:i') }}"
                                    class="form-control @error('created_at') is-invalid @enderror" required>
                                @error('created_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
   <div class="col-md-4">
    <label class="form-label">Trạng thái duyệt</label>
    @if ($isViolated)
        <div class="form-control bg-danger text-white">
            Vi phạm từ khóa cấm
        </div>
        <input type="hidden" name="approved" value="0">
    @else
        <select name="approved" class="form-select @error('approved') is-invalid @enderror" required>
            <option value="1" {{ $review->approved ? 'selected' : '' }}>Hiển thị công khai</option>
            <option value="0" {{ !$review->approved ? 'selected' : '' }}>Chờ kiểm duyệt</option>
        </select>
        @error('approved')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    @endif
</div>



                        <div class="mb-5">
                            <label class="form-label">Bình luận</label>
                            <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="4" required>{{ old('comment', $review->comment) }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save me-1"></i> Lưu thay đổi
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
