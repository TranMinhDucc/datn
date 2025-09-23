@extends('layouts.admin')

@section('content')
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Tạo Đơn Hàng Mới
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.orders.index') }}" class="text-muted text-hover-primary">Đơn hàng</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Tạo mới</li>
                    </ul>
                </div>
                <!--end::Page title-->
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                @if ($errors->any())
                    <div class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row p-5 mb-10">
                        <i class="ki-duotone ki-message-text-2 fs-2hx text-danger me-4 mb-5 mb-sm-0">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-danger">Có lỗi xảy ra!</h4>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                            <i class="ki-duotone ki-cross fs-1 text-danger">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>
                    </div>
                @endif

                <form action="{{ route('admin.orders.store') }}" method="POST" id="kt_order_form">
                    @csrf

                    <!--begin::Card-->
                    <div class="card mb-7">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Thông tin đơn hàng</h2>
                            </div>
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            <div class="row mb-8">
                                <!--begin::Col-->
                                <div class="col-xl-6">
                                    <div class="row g-9 mb-8">
                                        <!--begin::Col-->
                                        <div class="col-md-12 fv-row">
                                            <label class="required fs-6 fw-semibold mb-2">Người dùng</label>
                                            <select name="user_id" id="user_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Chọn người dùng" required>
                                                <option value="">Chọn người dùng</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Col-->
                                    </div>

                                    <div class="row g-9 mb-8">
                                        <!--begin::Col-->
                                        <div class="col-md-6 fv-row">
                                            <label class="required fs-6 fw-semibold mb-2">Phương thức thanh toán</label>
                                            <select name="payment_method_id" id="payment_method_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Chọn phương thức" required>
                                                <option value="">Chọn phương thức</option>
                                                @foreach ($paymentMethods as $method)
                                                    <option value="{{ $method->id }}" {{ old('payment_method_id') == $method->id ? 'selected' : '' }}>
                                                        {{ $method->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Col-->

                                        <!--begin::Col-->
                                        <div class="col-md-6 fv-row">
                                            <label class="required fs-6 fw-semibold mb-2">Phương thức vận chuyển</label>
                                            <select name="shipping_method" id="shipping_method" class="form-select form-select-solid" data-control="select2" data-placeholder="Chọn phương thức" required>
                                                <option value="">Chọn phương thức</option>
                                                @foreach ($shippingMethods as $method)
                                                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Col-->
                                    </div>

                                    <div class="row g-9 mb-8">
                                        <!--begin::Col-->
                                        <div class="col-md-12 fv-row">
                                            <label class="required fs-6 fw-semibold mb-2">Địa chỉ giao hàng</label>
                                            <select name="address_id" id="address_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Chọn địa chỉ" required>
                                                <option value="">Chọn địa chỉ</option>
                                                {{-- Sẽ load bằng JS --}}
                                            </select>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                </div>
                                <!--end::Col-->

                                <!--begin::Col-->
                                <div class="col-xl-6">
                                    <div class="row g-9 mb-8">
                                        <!--begin::Col-->
                                        <div class="col-md-6 fv-row">
                                            <label class="fs-6 fw-semibold mb-2">Phí vận chuyển</label>
                                            <div class="position-relative">
                                                <input type="number" name="shipping_fee" id="shipping_fee" class="form-control form-control-solid" value="0" min="0" placeholder="0">
                                                <span class="position-absolute top-50 end-0 translate-middle-y fs-6 text-gray-400 me-5">VNĐ</span>
                                            </div>
                                        </div>
                                        <!--end::Col-->

                                        <!--begin::Col-->
                                        <div class="col-md-6 fv-row">
                                            <label class="fs-6 fw-semibold mb-2">VAT (nếu có)</label>
                                            <div class="position-relative">
                                                <input type="number" name="tax_amount" id="tax_amount" class="form-control form-control-solid" value="0" min="0" placeholder="0">
                                                <span class="position-absolute top-50 end-0 translate-middle-y fs-6 text-gray-400 me-5">VNĐ</span>
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>

                                    <div class="row g-9 mb-8">
                                        <!--begin::Col-->
                                        <div class="col-md-6 fv-row">
                                            <label class="fs-6 fw-semibold mb-2">Giảm giá</label>
                                            <div class="position-relative">
                                                <input type="number" name="discount_amount" id="discount_amount" class="form-control form-control-solid" value="0" min="0" placeholder="0">
                                                <span class="position-absolute top-50 end-0 translate-middle-y fs-6 text-gray-400 me-5">VNĐ</span>
                                            </div>
                                        </div>
                                        <!--end::Col-->

                                        <!--begin::Col-->
                                        <div class="col-md-6 fv-row">
                                            <label class="fs-6 fw-semibold mb-2">Yêu cầu giao hàng</label>
                                            <select name="required_note_shipper" id="required_note_shipper" class="form-select form-select-solid" data-control="select2">
                                                <option value="KHONGCHOXEMHANG">Không cho xem hàng</option>
                                                <option value="CHOXEMHANGKHONGTHU">Cho xem nhưng không thử</option>
                                                <option value="CHOTHUHANG">Cho thử hàng</option>
                                            </select>
                                        </div>
                                        <!--end::Col-->
                                    </div>

                                    <div class="row g-9 mb-8">
                                        <!--begin::Col-->
                                        <div class="col-md-12 fv-row">
                                            <label class="fs-6 fw-semibold mb-2">Ghi chú cho shipper</label>
                                            <input type="text" name="note_shipper" id="note_shipper" class="form-control form-control-solid" placeholder="Nhập ghi chú cho shipper">
                                        </div>
                                        <!--end::Col-->
                                    </div>

                                    <div class="row g-9 mb-8">
                                        <!--begin::Col-->
                                        <div class="col-md-12 fv-row">
                                            <label class="fs-6 fw-semibold mb-2">Ghi chú đơn hàng</label>
                                            <textarea name="note" id="note" class="form-control form-control-solid" rows="3" placeholder="Nhập ghi chú đơn hàng"></textarea>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->

                    <!--begin::Card-->
                    <div class="card mb-7">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Sản phẩm trong đơn hàng</h2>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-sm btn-primary" id="add-item">
                                    <i class="ki-duotone ki-plus fs-2"></i>
                                    Thêm sản phẩm
                                </button>
                            </div>
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            <div id="items-container">
                                <div class="item-row border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="row g-9 mb-5">
                                        <!--begin::Col-->
                                        <div class="col-md-4 fv-row">
                                            <label class="required fs-6 fw-semibold mb-2">Sản phẩm</label>
                                            <select name="items[0][product_id]" id="product_id_0" class="form-select form-select-solid product-select" data-control="select2" data-placeholder="Chọn sản phẩm" required>
                                                <option value="">Chọn sản phẩm</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Col-->

                                        <!--begin::Col-->
                                        <div class="col-md-4 fv-row">
                                            <label class="fs-6 fw-semibold mb-2">Biến thể (nếu có)</label>
                                            <select name="items[0][variant_id]" id="variant_id_0" class="form-select form-select-solid variant-select" data-control="select2" data-placeholder="Không có biến thể">
                                                <option value="">Không có biến thể</option>
                                            </select>
                                        </div>
                                        <!--end::Col-->

                                        <!--begin::Col-->
                                        <div class="col-md-3 fv-row">
                                            <label class="required fs-6 fw-semibold mb-2">Số lượng</label>
                                            <input type="number" name="items[0][quantity]" id="quantity_0" class="form-control form-control-solid" min="1" required placeholder="1">
                                        </div>
                                        <!--end::Col-->

                                        <!--begin::Col-->
                                        <div class="col-md-1 fv-row">
                                            <label class="fs-6 fw-semibold mb-2">&nbsp;</label>
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="btn btn-sm btn-icon btn-light-danger remove-item">
                                                    <i class="ki-duotone ki-trash fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>
                                                </button>
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->

                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-light me-5">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Tạo đơn hàng</span>
                            <span class="indicator-progress">Đang xử lý...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->

                </form>
            </div>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let itemIndex = 0;

            // Khởi tạo Select2
            $('[data-control="select2"]').select2();

            // Thêm sản phẩm
            $('#add-item').click(function() {
                itemIndex++;
                const newRow = `
                    <div class="item-row border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                        <div class="row g-9 mb-5">
                            <div class="col-md-4 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Sản phẩm</label>
                                <select name="items[${itemIndex}][product_id]" id="product_id_${itemIndex}" class="form-select form-select-solid product-select" data-control="select2" data-placeholder="Chọn sản phẩm" required>
                                    <option value="">Chọn sản phẩm</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Biến thể (nếu có)</label>
                                <select name="items[${itemIndex}][variant_id]" id="variant_id_${itemIndex}" class="form-select form-select-solid variant-select" data-control="select2" data-placeholder="Không có biến thể">
                                    <option value="">Không có biến thể</option>
                                </select>
                            </div>
                            <div class="col-md-3 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Số lượng</label>
                                <input type="number" name="items[${itemIndex}][quantity]" id="quantity_${itemIndex}" class="form-control form-control-solid" min="1" required placeholder="1">
                            </div>
                            <div class="col-md-1 fv-row">
                                <label class="fs-6 fw-semibold mb-2">&nbsp;</label>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-sm btn-icon btn-light-danger remove-item">
                                        <i class="ki-duotone ki-trash fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#items-container').append(newRow);
                
                // Khởi tạo Select2 cho các element mới
                $(`#product_id_${itemIndex}`).select2();
                $(`#variant_id_${itemIndex}`).select2();
                
                updateVariants(itemIndex); // Cập nhật biến thể cho dòng mới
            });

            // Xóa sản phẩm
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
            });

            // Cập nhật biến thể khi chọn sản phẩm
            function updateVariants(index) {
                $('#product_id_' + index).change(function() {
                    const productId = $(this).val();
                    const variantSelect = $('#variant_id_' + index);
                    variantSelect.empty();

                    if (productId && typeof productVariants !== 'undefined' && productVariants[productId]) {
                        const variants = productVariants[productId];

                        if (variants.length > 0) {
                            // Có biến thể ⇒ chỉ hiển thị các biến thể
                            $.each(variants, function(i, variant) {
                                variantSelect.append('<option value="' + variant.id + '">' + variant
                                    .variant_name + ' (Giá: ' + variant.price + ' VNĐ)' +
                                    '</option>');
                            });
                            // Auto chọn biến thể đầu tiên
                            variantSelect.val(variants[0].id).trigger('change');
                        } else {
                            // Không có biến thể ⇒ thêm dòng "Không có biến thể"
                            variantSelect.append('<option value="">Không có biến thể</option>');
                        }
                    } else {
                        // Không có dữ liệu biến thể ⇒ thêm dòng "Không có biến thể"
                        variantSelect.append('<option value="">Không có biến thể</option>');
                    }
                });
            }

            // Khởi tạo cho dòng đầu tiên
            updateVariants(0);

            // Form validation
            $('#kt_order_form').on('submit', function() {
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.find('.indicator-label').hide();
                submitBtn.find('.indicator-progress').show();
                submitBtn.prop('disabled', true);
            });
        });

        // Dữ liệu biến thể từ server
        var productVariants = @json($productVariants);

        // Load địa chỉ khi chọn user
        $('#user_id').change(function() {
            let userId = $(this).val();
            let addressSelect = $('#address_id');
            addressSelect.empty().append('<option value="">Đang tải...</option>');

            if (userId) {
                $.get("{{ url('/admin/users') }}/" + userId + "/addresses", function(data) {
                    addressSelect.empty().append('<option value="">Chọn địa chỉ</option>');
                    if (data.length > 0) {
                        $.each(data, function(i, addr) {
                            addressSelect.append(
                                `<option value="${addr.id}">
                            ${addr.full_name} - ${addr.address}
                        </option>`
                            );
                        });
                    } else {
                        addressSelect.append('<option value="">(Người dùng chưa có địa chỉ)</option>');
                    }
                }).fail(function() {
                    addressSelect.empty().append('<option value="">Lỗi tải dữ liệu</option>');
                });
            } else {
                addressSelect.empty().append('<option value="">Chọn địa chỉ</option>');
            }
        });
    </script>
@endsection