@extends('layouts.admin')
@section('title', 'Chỉnh sửa thông tin cửa hàng')
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!-- Toolbar -->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Cài đặt địa chỉ lấy hàng
                    </h1>
                    <!--end::Title-->


                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="../../../index.html" class="text-muted text-hover-primary">
                                Home </a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->

                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            eCommerce </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->

                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            Catalog </li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <div class="m-0">
                        <a href="#" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                            <i class="fa-solid fa-filter fs-6 text-muted me-1"><span class="path1"></span><span
                                    class="path2"></span></i>
                            Filter
                        </a>
                        <!--end::Menu toggle-->



                        <!--begin::Menu 1-->
                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                            id="kt_menu_683db6e8d632c">
                            <!--begin::Header-->
                            <div class="px-7 py-5">
                                <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                            </div>
                            <!--end::Header-->

                            <!--begin::Menu separator-->
                            <div class="separator border-gray-200"></div>
                            <!--end::Menu separator-->


                            <!--begin::Form-->
                            <div class="px-7 py-5">
                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Status:</label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <div>
                                        <select class="form-select form-select-solid" multiple data-kt-select2="true"
                                            data-close-on-select="false" data-placeholder="Select option"
                                            data-dropdown-parent="#kt_menu_683db6e8d632c" data-allow-clear="true">
                                            <option></option>
                                            <option value="1">Approved</option>
                                            <option value="2">Pending</option>
                                            <option value="2">In Process</option>
                                            <option value="2">Rejected</option>
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Member Type:</label>
                                    <div class="d-flex">
                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                            <input class="form-check-input" type="checkbox" value="1" />
                                            <span class="form-check-label">
                                                Author
                                            </span>
                                        </label>
                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="2"
                                                checked="checked" />
                                            <span class="form-check-label">
                                                Customer
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-10">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold">Notifications:</label>
                                    <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="" name="notifications"
                                            checked />
                                        <label class="form-check-label">
                                            Enabled
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2"
                                        data-kt-menu-dismiss="true">Reset</button>

                                    <button type="submit" class="btn btn-sm btn-primary"
                                        data-kt-menu-dismiss="true">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.shipping-fees.create') }}" class="btn btn-sm fw-bold btn-primary">
                        Create </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div class="app-container container-xxl">
                <div class="card">
                    <div class="card-body p-10">
                        <form action="{{ route('admin.shopSettings.update') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Tên cửa hàng</label>
                                <input type="text" class="form-control" name="shop_name"
                                    value="{{ old('shop_name', $setting->shop_name ?? '') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" name="shop_phone"
                                    value="{{ old('shop_phone', $setting->shop_phone ?? '') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ cụ thể</label>
                                <input type="text" class="form-control" name="address"
                                    value="{{ old('address', $setting->address ?? '') }}" required>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Tỉnh/Thành phố</label>
                                    <select class="form-select" name="province_id" id="province-select" required>
                                        <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}" @selected(old('province_id', $setting->province_id ?? '') == $province->id)>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Quận/Huyện</label>
                                    <select class="form-select" name="district_id" id="district-select" required>
                                        <option value="">-- Chọn Quận/Huyện --</option>
                                        @if (!empty($districts))
                                            @foreach ($districts as $district)
                                                <option value="{{ $district->id }}" @selected(old('district_id', $setting->district_id ?? '') == $district->id)>
                                                    {{ $district->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Xã/Phường</label>
                                    <select class="form-select" name="ward_id" id="ward-select" required>
                                        <option value="">-- Chọn Xã/Phường --</option>
                                        @if (!empty($wards))
                                            @foreach ($wards as $ward)
                                                <option value="{{ $ward->id }}" @selected(old('ward_id', $setting->ward_id ?? '') == $ward->id)>
                                                    {{ $ward->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Lưu cấu hình</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const provinceSelect = $('#province-select');
        const districtSelect = $('#district-select');
        const wardSelect = $('#ward-select');

        provinceSelect.on('change', function() {
            const provinceId = $(this).val();
            districtSelect.html('<option value="">-- Đang tải Quận/Huyện --</option>');
            wardSelect.html('<option value="">-- Chọn Xã/Phường --</option>');

            if (provinceId) {
                $.get(`/api/districts?province_id=${provinceId}`, function(data) {
                    let options = '<option value="">-- Chọn Quận/Huyện --</option>';
                    data.forEach(function(item) {
                        options += `<option value="${item.id}">${item.name}</option>`;
                    });
                    districtSelect.html(options);
                });
            }
        });

        districtSelect.on('change', function() {
            const districtId = $(this).val();
            wardSelect.html('<option value="">-- Đang tải Xã/Phường --</option>');

            if (districtId) {
                $.get(`/api/wards?district_id=${districtId}`, function(data) {
                    let options = '<option value="">-- Chọn Xã/Phường --</option>';
                    data.forEach(function(item) {
                        options += `<option value="${item.id}">${item.name}</option>`;
                    });
                    wardSelect.html(options);
                });
            }
        });
    </script>
    <script>
        const provinceSelect = $('#province-select');
        const districtSelect = $('#district-select');
        const wardSelect = $('#ward-select');

        // Lấy district khi đã có sẵn province_id
        function loadDistricts(provinceId, selectedDistrictId = null) {
            if (!provinceId) return;

            $.get(`/api/districts?province_id=${provinceId}`, function(data) {
                let html = '<option value="">-- Chọn Quận/Huyện --</option>';
                data.forEach(d => {
                    const selected = selectedDistrictId == d.id ? 'selected' : '';
                    html += `<option value="${d.id}" ${selected}>${d.name}</option>`;
                });
                districtSelect.html(html);

                // Nếu có sẵn district_id thì gọi tiếp để lấy ward
                if (selectedDistrictId) {
                    loadWards(selectedDistrictId, '{{ old('ward_id', $setting->ward_id ?? '') }}');
                }
            });
        }

        // Lấy ward khi đã có sẵn district_id
        function loadWards(districtId, selectedWardId = null) {
            if (!districtId) return;

            $.get(`/api/wards?district_id=${districtId}`, function(data) {
                let html = '<option value="">-- Chọn Xã/Phường --</option>';
                data.forEach(w => {
                    const selected = selectedWardId == w.id ? 'selected' : '';
                    html += `<option value="${w.id}" ${selected}>${w.name}</option>`;
                });
                wardSelect.html(html);
            });
        }

        // Khi chọn tỉnh
        provinceSelect.on('change', function() {
            const provinceId = $(this).val();
            districtSelect.html('<option value="">-- Đang tải Quận/Huyện --</option>');
            wardSelect.html('<option value="">-- Chọn Xã/Phường --</option>');
            loadDistricts(provinceId);
        });

        // Khi chọn quận
        districtSelect.on('change', function() {
            const districtId = $(this).val();
            wardSelect.html('<option value="">-- Đang tải Xã/Phường --</option>');
            loadWards(districtId);
        });

        // ✅ Auto-load nếu có sẵn dữ liệu
        const initialProvince = '{{ old('province_id', $setting->province_id ?? '') }}';
        const initialDistrict = '{{ old('district_id', $setting->district_id ?? '') }}';
        if (initialProvince && initialDistrict) {
            loadDistricts(initialProvince, initialDistrict);
        }
    </script>
@endpush
