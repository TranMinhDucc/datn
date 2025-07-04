@extends('layouts.client')

@section('title', 'Chỉnh sửa địa chỉ')

@section('content')
    <div class="container py-4 text-dark" style="color: #f10808;">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white">
                <h4 style="color: #ffffff; font-weight: bold; margin-bottom: 1rem;">Chỉnh sửa địa chỉ</h4>

            </div>

            <div class="card-body">
                <form action="{{ route('client.account.address.update', $address->id) }}" method="POST" class="row g-3"
                    id="address-form">
                    @csrf
                    @method('PUT')

                    <div class="col-12">
                        <label class="form-label">Loại địa chỉ</label>
                        <select class="form-select @error('title') is-invalid @enderror" name="title">
                            <option value="">-- Chọn loại --</option>
                            <option value="Nhà riêng" {{ old('title', $address->title) == 'Nhà riêng' ? 'selected' : '' }}>
                                Nhà riêng</option>
                            <option value="Công ty" {{ old('title', $address->title) == 'Công ty' ? 'selected' : '' }}>Công
                                ty</option>
                            <option value="Khác" {{ old('title', $address->title) == 'Khác' ? 'selected' : '' }}>Khác
                            </option>
                        </select>
                        @error('title')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6">
                        <label class="form-label">Điện thoại</label>
                        <input class="form-control @error('phone') is-invalid @enderror" type="text" name="phone"
                            value="{{ old('phone', $address->phone) }}">
                        @error('phone')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6">
                        <label class="form-label">Mã bưu chính</label>
                        <input class="form-control @error('pincode') is-invalid @enderror" name="pincode"
                            value="{{ old('pincode', $address->pincode) }}">
                        @error('pincode')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" class="form-control" name="country" value="Vietnam">

                    <div class="col-4">
                        <label class="form-label">Tỉnh/Thành phố</label>
                        <select class="form-select" name="province_id" id="province-select" required>
                            <option value="">-- Chọn tỉnh --</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->id }}"
                                    {{ old('province_id', $address->province_id) == $province->id ? 'selected' : '' }}>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('province_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-4">
                        <label class="form-label">Quận/Huyện</label>
                        <select class="form-select" name="district_id" id="district-select" required>
                            <option value="">-- Chọn huyện --</option>
                        </select>
                        @error('district_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-4">
                        <label class="form-label">Phường/Xã</label>
                        <select class="form-select" name="ward_id" id="ward-select" required>
                            <option value="">-- Chọn xã --</option>
                        </select>
                        @error('ward_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Địa chỉ chi tiết</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address', $address->address) }}</textarea>
                        @error('address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
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
    <script>
        const provinceId = {{ old('province_id', $address->province_id ?? 'null') }};
        const districtId = {{ old('district_id', $address->district_id ?? 'null') }};
        const wardId = {{ old('ward_id', $address->ward_id ?? 'null') }};

        // Load districts when province selected
        $('#province-select').on('change', function() {
            const province = $(this).val();
            $('#district-select').html('<option value="">-- Đang tải huyện --</option>');
            $('#ward-select').html('<option value="">-- Chọn xã --</option>');
            if (province) {
                $.get(`/api/districts?province_id=${province}`, function(data) {
                    let html = '<option value="">-- Chọn huyện --</option>';
                    data.forEach(i => html += `<option value="${i.id}">${i.name}</option>`);
                    $('#district-select').html(html);
                    if (districtId) $('#district-select').val(districtId).trigger('change');
                });
            }
        });

        $('#district-select').on('change', function() {
            const district = $(this).val();
            $('#ward-select').html('<option value="">-- Đang tải xã --</option>');
            if (district) {
                $.get(`/api/wards?district_id=${district}`, function(data) {
                    let html = '<option value="">-- Chọn xã --</option>';
                    data.forEach(i => html += `<option value="${i.id}">${i.name}</option>`);
                    $('#ward-select').html(html);
                    if (wardId) $('#ward-select').val(wardId);
                });
            }
        });

        // Gọi ban đầu
        if (provinceId) {
            $('#province-select').val(provinceId).trigger('change');
        }
    </script>

@endsection
