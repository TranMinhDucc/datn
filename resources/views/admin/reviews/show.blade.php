@extends('layouts.admin')

@section('title', 'Chi tiết đánh giá')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Chi tiết đánh giá</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <label class="col-sm-3 fw-bold text-end">Người dùng:</label>
                <div class="col-sm-9">
                    {{ $review->user->username ?? 'N/A' }}
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 fw-bold text-end">Sản phẩm:</label>
                <div class="col-sm-9">
                    {{ $review->product->name ?? 'N/A' }}
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-3 fw-bold text-end">Đánh giá:</label>
                <div class="col-sm-9 d-flex align-items-center">
                    <span class="fs-5 me-2">{{ $review->rating }}</span>
                    <i class="fa fa-star text-warning fs-5"></i>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 fw-bold text-end">Bình luận:</label>
                <div class="col-sm-9">
                    {!! nl2br(e($review->comment ?? 'Không có')) !!}
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 fw-bold text-end">Xác minh:</label>
                <div class="col-sm-9">
                    @if($review->verified_purchase)
                        <span class="badge bg-success">Đã xác minh</span>
                    @else
                        <span class="badge bg-secondary">Chưa xác minh</span>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 fw-bold text-end">Thời gian:</label>
                <div class="col-sm-9">
                    {{ $review->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-primary">
                <i class="fa fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>
</div>
@endsection
