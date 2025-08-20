@extends('layouts.admin')

@section('title', 'Tạo mã giảm giá mới')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div class="app-content container-xxl">
            <div class="card card-flush">
                <div class="card-header">
                    <h2 class="card-title">Tạo mã giảm giá</h2>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Lỗi!</strong> Vui lòng kiểm tra lại dữ liệu:
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.coupons.store') }}" method="POST">
                        @csrf

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mã giảm giá</label>
                                <input type="text" name="code" class="form-control form-control-solid" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Loại áp dụng</label>
                                <select name="type" class="form-select form-select-solid" required>
                                    <option value="product_discount">Sản phẩm</option>
                                    <option value="shipping_discount">Phí vận chuyển</option>
                                    <option value="order_discount">Toàn đơn hàng</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kiểu giảm</label>
                                <select name="value_type" class="form-select form-select-solid" required>
                                    <option value="percentage">Phần trăm</option>
                                    <option value="fixed">Số tiền cố định</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Giá trị giảm</label>
                                <input type="number" step="0.01" name="discount_value"
                                    class="form-control form-control-solid" required>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Giảm tối đa (nếu là %)</label>
                                <input type="number" step="0.01" name="max_discount_amount"
                                    class="form-control form-control-solid">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Giá trị đơn tối thiểu</label>
                                <input type="number" step="0.01" name="min_order_amount"
                                    class="form-control form-control-solid">
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tổng số lượt sử dụng</label>
                                <input type="number" name="usage_limit" class="form-control form-control-solid">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số lượt mỗi người dùng</label>
                                <input type="number" name="per_user_limit" class="form-control form-control-solid">
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ngày bắt đầu</label>
                                <input type="datetime-local" name="start_date" class="form-control form-control-solid">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ngày kết thúc</label>
                                <input type="datetime-local" name="end_date" class="form-control form-control-solid">
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-4">
                                <label class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="only_for_new_users"
                                        value="1">
                                    <span class="form-check-label">Chỉ dành cho người dùng mới</span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="is_exclusive" value="1">
                                    <span class="form-check-label">Không dùng chung với mã khác</span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="active" value="1" checked>
                                    <span class="form-check-label">Kích hoạt mã</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="applyAllProducts" name="apply_all_products"
                                value="1" checked>
                            <label class="form-check-label fw-semibold" for="applyAllProducts">Áp dụng cho tất cả sản
                                phẩm</label>
                        </div>

                        <div class="mb-6">
                            <label class="form-label fw-semibold">Áp dụng cho sản phẩm (ID cách nhau bởi dấu phẩy)</label>
                            <input type="text" name="applicable_product_ids" id="productIdsInput"
                                class="form-control form-control-solid" placeholder="VD: 1,2,3" disabled>
                        </div>

                        {{-- <div class="mb-6">
                        <label class="form-label fw-semibold">Áp dụng cho danh mục (ID cách nhau bởi dấu phẩy)</label>
                        <input type="text" name="applicable_category_ids" class="form-control form-control-solid" placeholder="VD: 3,4,7">
                    </div> --}}

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-light">Hủy</a>
                            <button type="submit" class="btn btn-primary">Tạo mới</button>
                        </div>
                    </form>
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
    @endpush
@endsection
