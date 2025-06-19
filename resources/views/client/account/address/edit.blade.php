@extends('layouts.client')

@section('title', 'Chỉnh sửa địa chỉ')

@section('content')
    <div class="container py-4 text-dark" style="color: #f10808;">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white">
                <h4 style="color: #ffffff; font-weight: bold; margin-bottom: 1rem;">Chỉnh sửa địa chỉ</h4>

            </div>

            <div class="card-body">
                <form action="{{ route('client.account.address.update', $address->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label" style="color: #333;">Loại Địa Chỉ</label>
                            <select name="title" class="form-select">
                                <option value="Nhà riêng" {{ $address->title == 'Nhà riêng' ? 'selected' : '' }}>Nhà Riêng
                                </option>
                                <option value="Công ty" {{ $address->title == 'Công ty' ? 'selected' : '' }}>Công Ty
                                </option>
                                <option value="Khác" {{ $address->title == 'Khác' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="color: #333;">Số Điện Thoại</label>
                            <input type="text" name="phone" class="form-control" value="{{ $address->phone }}"
                                placeholder="Nhập số điện thoại">
                        </div>

                        <div class="col-12">
                            <label class="form-label" style="color: #333;">Địa Chỉ Chi Tiết</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Nhập địa chỉ">{{ $address->address }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="color: #333;">Quốc Gia</label>
                            <input type="text" name="country" class="form-control" value="{{ $address->country }}"
                                placeholder="Nhập quốc gia">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="color: #333;">Tỉnh / Thành Phố</label>
                            <input type="text" name="state" class="form-control" value="{{ $address->state }}"
                                placeholder="Nhập tỉnh hoặc thành phố">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="color: #333;">Quận / Huyện</label>
                            <input type="text" name="city" class="form-control" value="{{ $address->city }}"
                                placeholder="Nhập quận hoặc huyện">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" style="color: #333;">Mã Bưu Chính</label>
                            <input type="text" name="pincode" class="form-control" value="{{ $address->pincode }}"
                                placeholder="Nhập mã bưu chính">
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('client.account.dashboard') }}" class="btn btn-outline-dark">
                            <i class="fas fa-arrow-left me-1"></i> Quay Lại
                        </a>
                        <button type="submit" class="btn btn-dark btn-lg px-5 py-2 fw-semibold">
                            Cập Nhật
                        </button>


                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
