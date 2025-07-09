@extends('layouts.admin')
@section('title', isset($shippingFee) ? 'Cập nhật phí vận chuyển' : 'Thêm phí vận chuyển')
@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card card-flush">
                <div class="card-header pt-8">
                    <div class="card-header">
                        <h4 class="mb-0">{{ isset($shippingFee) ? 'Cập nhật' : 'Thêm mới' }} phí vận chuyển</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <form method="POST"
                            action="{{ isset($shippingFee) ? route('admin.shipping-fees.update', $shippingFee->id) : route('admin.shipping-fees.store') }}">
                            @csrf
                            @if (isset($shippingFee))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label for="shipping_zone_id" class="form-label">Khu vực</label>
                                <select name="shipping_zone_id" class="form-select"
                                    {{ isset($shippingFee) ? 'disabled' : '' }} required>
                                    <option value="">-- Chọn khu vực --</option>
                                    @foreach ($zones as $zone)
                                        <option value="{{ $zone->id }}"
                                            {{ old('shipping_zone_id', $shippingFee->shipping_zone_id ?? '') == $zone->id ? 'selected' : '' }}>
                                            {{ $zone->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if (isset($shippingFee))
                                    <input type="hidden" name="shipping_zone_id"
                                        value="{{ $shippingFee->shipping_zone_id }}">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="shipping_method_id" class="form-label">Phương thức giao hàng</label>
                                <select name="shipping_method_id" class="form-select"
                                    {{ isset($shippingFee) ? 'disabled' : '' }} required>
                                    <option value="">-- Chọn phương thức --</option>
                                    @foreach ($methods as $method)
                                        <option value="{{ $method->id }}"
                                            {{ old('shipping_method_id', $shippingFee->shipping_method_id ?? '') == $method->id ? 'selected' : '' }}>
                                            {{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if (isset($shippingFee))
                                    <input type="hidden" name="shipping_method_id"
                                        value="{{ $shippingFee->shipping_method_id }}">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Phí giao hàng (VNĐ)</label>
                                <input type="number" class="form-control" name="price"
                                    value="{{ old('price', $shippingFee->price ?? '') }}" required min="0">
                            </div>

                            <div class="mb-3">
                                <label for="free_shipping_minimum" class="form-label">Miễn phí từ đơn hàng (VNĐ)</label>
                                <input type="number" class="form-control" name="free_shipping_minimum"
                                    value="{{ old('free_shipping_minimum', $shippingFee->free_shipping_minimum ?? '') }}"
                                    min="0">
                            </div>

                            <button type="submit"
                                class="btn btn-primary">{{ isset($shippingFee) ? 'Cập nhật' : 'Thêm mới' }}</button>
                            <a href="{{ route('admin.shipping-fees.index') }}" class="btn btn-secondary">Quay lại</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>
@endsection
