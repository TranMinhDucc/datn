@extends('layouts.admin')

@section('title', 'Chỉnh sửa mã giảm giá')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">Chỉnh sửa mã giảm giá</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-light">
                    <i class="fa-solid fa-arrow-left fs-6 me-1"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="container-xxl">
            <div class="card card-flush">
                <div class="card-header py-5">
                    <div class="card-title">
                        <h2>Thông tin mã giảm giá</h2>
                    </div>
                </div>

                <div class="card-body pt-0">


                    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mã giảm giá</label>
                                <input type="text" name="code" class="form-control form-control-solid"
                                    value="{{ $coupon->code }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Loại áp dụng</label>
                                <select name="type" class="form-select form-select-solid" required>
                                    <option value="product_discount"
                                        {{ $coupon->type === 'product_discount' ? 'selected' : '' }}>Sản phẩm</option>
                                    <option value="shipping_discount"
                                        {{ $coupon->type === 'shipping_discount' ? 'selected' : '' }}>Phí vận chuyển
                                    </option>
                                    
                                </select>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kiểu giảm</label>
                                <select id="valueTypeSelect" name="value_type" class="form-select form-select-solid" required>
                                    <option value="percentage" {{ old('value_type', $coupon->value_type) === 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                                    <option value="fixed" {{ old('value_type', $coupon->value_type) === 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label id="discountValueLabel" class="form-label fw-semibold">
                                    {{ old('value_type', $coupon->value_type) === 'percentage' ? 'Giá trị giảm (%)' : 'Giá trị giảm (đ)' }}
                                </label>
                                <input id="discountValueInput" type="number" step="0.01" name="discount_value"
                                    class="form-control form-control-solid"
                                    value="{{ old('discount_value', $coupon->discount_value) }}" required>
                            </div>
                        </div>

                        <div class="row mb-6">
                            {{-- Giảm tối đa (chỉ hiện khi là %) --}}
                            <div class="col-md-6 {{ old('value_type', $coupon->value_type) === 'percentage' ? '' : 'd-none' }}" id="maxDiscountGroup">
                                <label class="form-label fw-semibold">Giảm tối đa (nếu là %)</label>
                                <input type="number" step="0.01" name="max_discount_amount" id="maxDiscountInput"
                                    class="form-control form-control-solid"
                                    value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}"
                                    {{ old('value_type', $coupon->value_type) === 'percentage' ? '' : 'disabled' }}>
                                <small id="maxDiscountNote" class="text-muted">
                                    {{ old('value_type', $coupon->value_type) === 'percentage' ? 'Giới hạn phần trăm tối đa áp dụng (≤ 100%).' : '' }}
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Giá trị đơn tối thiểu</label>
                                <input type="number" step="0.01" name="min_order_amount"
                                    class="form-control form-control-solid"
                                    value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                            </div>
                        </div>


                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tổng số lượt sử dụng</label>
                                <input type="number" name="usage_limit" class="form-control form-control-solid"
                                    value="{{ $coupon->usage_limit }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số lượt mỗi người dùng</label>
                                <input type="number" name="per_user_limit" class="form-control form-control-solid"
                                    value="{{ $coupon->per_user_limit }}">
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ngày bắt đầu</label>
                                <input type="datetime-local" name="start_date" class="form-control form-control-solid"
                                    value="{{ \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d\TH:i') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ngày kết thúc</label>
                                <input type="datetime-local" name="end_date" class="form-control form-control-solid"
                                    value="{{ \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d\TH:i') }}">
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-4">
                                <label class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="only_for_new_users"
                                        value="1" {{ $coupon->only_for_new_users ? 'checked' : '' }}>
                                    <span class="form-check-label">Chỉ dành cho người dùng mới</span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="is_exclusive"
                                        value="1" {{ $coupon->is_exclusive ? 'checked' : '' }}>
                                    <span class="form-check-label">Không dùng chung với mã khác</span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="active" value="1"
                                        {{ $coupon->active ? 'checked' : '' }}>
                                    <span class="form-check-label">Kích hoạt mã</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="applyAllProducts"
                                name="apply_all_products" value="1"
                                {{ is_null($coupon->applicable_product_ids) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="applyAllProducts">Áp dụng cho tất cả sản phẩm</label>
                        </div>

                        <div class="mb-6">
                            <label class="form-label fw-semibold">Áp dụng cho sản phẩm (ID, phân cách bằng dấu phẩy)</label>
                            <input type="text" name="applicable_product_ids" id="productIdsInput"
                                class="form-control form-control-solid"
                                value="{{ is_array($coupon->applicable_product_ids) ? implode(',', $coupon->applicable_product_ids) : '' }}"
                                {{ is_null($coupon->applicable_product_ids) ? 'disabled' : '' }}>
                        </div>



                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-light">Hủy</a>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('applyAllProducts').addEventListener('change', function() {
        document.getElementById('productIdsInput').disabled = this.checked;
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Apply-all-products (đoạn của bạn)
  const applyAll = document.getElementById('applyAllProducts');
  const productIdsInput = document.getElementById('productIdsInput');
  if (applyAll && productIdsInput) {
    applyAll.addEventListener('change', function () {
      productIdsInput.disabled = this.checked;
    });
  }

  // Ẩn/hiện Giảm tối đa theo Kiểu giảm
  const valueTypeSelect = document.getElementById('valueTypeSelect');
  const maxGroup       = document.getElementById('maxDiscountGroup');
  const maxInput       = document.getElementById('maxDiscountInput');
  const note           = document.getElementById('maxDiscountNote');
  const discountInput  = document.getElementById('discountValueInput');
  const discountLabel  = document.getElementById('discountValueLabel');

  function syncUI() {
    const isPercent = valueTypeSelect.value === 'percentage';

    // Show/Hide & enable/disable "Giảm tối đa"
    maxGroup.classList.toggle('d-none', !isPercent);
    maxInput.disabled = !isPercent;
    if (!isPercent) { maxInput.value = ''; }

    // Đổi nhãn & ràng buộc cho "Giá trị giảm"
    if (isPercent) {
      discountLabel.textContent = 'Giá trị giảm (%)';
      discountInput.setAttribute('max', '100');   // phụ trợ phía client
      discountInput.setAttribute('step', '0.01');
      note.textContent = 'Giới hạn phần trăm tối đa áp dụng (≤ 100%).';
    } else {
      discountLabel.textContent = 'Giá trị giảm (đ)';
      discountInput.removeAttribute('max');
      discountInput.setAttribute('step', '0.01');
      note.textContent = '';
    }
  }

  if (valueTypeSelect) {
    valueTypeSelect.addEventListener('change', syncUI);
    syncUI(); // chạy lần đầu theo giá trị hiện tại (bao gồm khi back từ lỗi validate)
  }
});
</script>
@endpush
@endsection