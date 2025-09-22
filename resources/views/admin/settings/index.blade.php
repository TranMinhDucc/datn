@extends('layouts.admin')
@section('title', 'Danh sách sản phẩm')
@section('content')
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">

            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">



                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Cài đặt
                    </h1>
                    <!--end::Title-->


                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="../../index.html" class="text-muted text-hover-primary">
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
                            Cài đặt </li>
                        <!--end::Item-->

                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content  flex-column-fluid ">


            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-xxl ">
                <!--begin::Card-->
                <div class="card card-flush">
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin:::Tabs-->
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-15">
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5 active"
                                    data-bs-toggle="tab" href="#kt_ecommerce_settings_general">
                                    <i class="fa-solid fa-gear fs-4 me-2"></i> Cài đặt chung

                                </a>
                            </li>
                            <!--end:::Tab item-->

                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5" data-bs-toggle="tab"
                                    href="#kt_ecommerce_settings_store">
                                    <i class="fa-solid fa-images fs-2 me-2"><span class="path1"></span><span
                                            class="path2"></span><span class="path3"></span><span
                                            class="path4"></span><span class="path5"></span></i> Theme
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5" data-bs-toggle="tab"
                                    href="#kt_ecommerce_settings_localization">
                                    <i class="fa-solid fa-plug fs-2 me-2"><span class="path1"></span><span
                                            class="path2"></span></i> Kết nối
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#kt_ecommerce_settings_notifications">
                                    <i class="fa-solid fa-bell fs-2 me-2"><span class="path1"></span><span
                                            class="path2"></span></i> Thông báo Telegram
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5" data-bs-toggle="tab"
                                    href="#kt_ecommerce_settings_products">
                                    <i class="fa-solid fa-cart-shopping fs-2 me-2"><span class="path1"></span><span
                                            class="path2"></span><span class="path3"></span></i> Sản phẩm
                                </a>
                            </li>
                            <!--end:::Tab item-->

                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5" data-bs-toggle="tab"
                                    href="#kt_ecommerce_settings_customers">
                                    <i class="ki-duotone ki-people fs-2 me-2"><span class="path1"></span><span
                                            class="path2"></span><span class="path3"></span><span
                                            class="path4"></span><span class="path5"></span></i> Customers
                                </a>
                            </li>
                            <!--end:::Tab item-->
                        </ul>
                        <!--end:::Tabs-->

                        <!--begin:::Tab content-->
                        <div class="tab-content" id="myTabContent">
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel">

                                <!--begin::Form-->
                                <form id="kt_ecommerce_settings_general_form" class="form"
                                    action="{{ route('admin.settings.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="tab" value="general">
                                    <!-- Xác định tab là 'general' -->
                                    <!--begin::Heading-->
                                    <div class="row mb-7">
                                        <div class="col-md-9 offset-md-3">
                                            <h2>Cài đặt chung</h2>
                                        </div>
                                    </div>
                                    <!--end::Heading-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="required">Meta Title</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Đặt tiêu đề cho cửa hàng để tối ưu hóa SEO.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-9">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-solid" name="title"
                                                value="{{ $settings['title']->value ?? '' }}" />
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Meta Tag Description</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Thiết lập mô tả cho cửa hàng để tối ưu hóa SEO.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-9">
                                            <!--begin::Input-->
                                            <textarea class="form-control form-control-solid" name="description">{{ $settings['description']->value ?? '' }}</textarea>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Meta Keywords</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Đặt từ khóa cho cửa hàng, phân tách bằng dấu phẩy.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-9">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-solid" name="keywords"
                                                value="{{ $settings['keywords']->value ?? '' }}"
                                                data-kt-ecommerce-settings-type="tagify" />
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Author</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Thiết lập chủ của cửa hàng.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-9">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-solid" name="author"
                                                value="{{ $settings['author']->value ?? '' }}" />
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Timezone</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Thiết lập chủ của cửa hàng.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-9">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-solid" name="timezone"
                                                value="{{ $settings['timezone']->value ?? '' }}" />
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Email</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Thiết lập Email của cửa hàng.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-9">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-solid" name="email"
                                                value="{{ $settings['email']->value ?? '' }}" />
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Phone</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Thiết lập hotline của cửa hàng.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-9">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-solid" name="hotline"
                                                value="{{ $settings['hotline']->value ?? '' }}" />
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="required">Address</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Set the store's full address.">
                                                    <span class="path1"></span><span class="path2"></span><span
                                                        class="path3"></span></i></span> </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <!--begin::Input-->
                                            <textarea class="form-control form-control-solid" name="address">{{ $settings['address']->value ?? '' }}</textarea>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Thuế</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Nếu bạn muốn thu thuế VAT thì nhập vào đây, nếu không có thì để trống.
                                                                Ví dụ bạn nhập 10% thì hệ thống sẽ tự động thêm 10% vào tổng số tiền thanh toán sau khi trừ khuyến mãi.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i>
                                                </span>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <div class="col-md-9">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-solid" name="vat"
                                                value="{{ $settings['vat']->value ?? '' }}" />
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="required">Chính sách đổi trả</span>
                                            </label>
                                        </div>
                                        <div class="col-md-9">
                                            <textarea id="return_policy" class="form-control form-control-solid" name="return_policy">{{ $settings['return_policy']->value ?? '' }}</textarea>
                                        </div>
                                    </div>

                                    <!--end::Input group-->
                                    <!--begin::Action buttons-->
                                    <div class="row py-5">
                                        <div class="col-md-9 offset-md-3">
                                            <div class="d-flex">
                                                <!--begin::Button-->
                                                <button type="reset" data-kt-ecommerce-settings-type="cancel"
                                                    class="btn btn-light me-3">
                                                    Cancel
                                                </button>
                                                <!--end::Button-->

                                                <!--begin::Button-->
                                                <button type="submit" data-kt-ecommerce-settings-type="submit"
                                                    class="btn btn-primary">
                                                    <span class="indicator-label">Save</span>
                                                    <span class="indicator-progress">Please wait... <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                                <!--end::Button-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Action buttons-->
                                </form>
                                <!--end::Form-->
                            </div>
                            <!--end:::Tab pane-->
                            {{-- THEME  --}}
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade" id="kt_ecommerce_settings_store" role="tabpanel">
                                <!--begin::Form-->
                                <form id="kt_ecommerce_settings_general_form" class="form"
                                    action="{{ route('admin.settings.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="tab" value="set_images">
                                    <!--begin::Heading-->
                                    <div class="row mb-7">
                                        <div class="col-md-9 offset-md-3">
                                            <h2>THAY ĐỔI GIAO DIỆN WEBSITE
                                            </h2>
                                        </div>
                                    </div>
                                    <!--end::Heading-->
                                    <!--begin::Input group - Logo Light-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Logo Light</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Upload the light version of the logo.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="file" class="form-control form-control-solid"
                                                name="logo_light" />
                                            @if ($settings['logo_light']->value)
                                                <img src="{{ asset('storage/' . $settings['logo_light']->value) }}"
                                                    alt="Logo Light" width="100">
                                            @endif
                                        </div>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group - Logo Light-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Logo Dark</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Upload the light version of the logo.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="file" class="form-control form-control-solid"
                                                name="logo_dark" />
                                            @if ($settings['logo_dark']->value)
                                                <img src="{{ asset('storage/' . $settings['logo_dark']->value) }}"
                                                    alt="Logo Dark" width="100">
                                            @endif
                                        </div>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group - Logo Light-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Favicon</span>
                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Upload the light version of the logo.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="file" class="form-control form-control-solid"
                                                name="favicon" />
                                            @if ($settings['favicon']->value)
                                                <img src="{{ asset('storage/' . $settings['favicon']->value) }}"
                                                    alt="Logo Dark" width="100">
                                            @endif
                                        </div>
                                    </div>
                                    <!--end::Input group-->


                                    <!--begin::Action buttons-->
                                    <div class="row py-5">
                                        <div class="col-md-9 offset-md-3">
                                            <div class="d-flex">
                                                <!--begin::Button-->
                                                <button type="reset" data-kt-ecommerce-settings-type="cancel"
                                                    class="btn btn-light me-3">
                                                    Cancel
                                                </button>
                                                <!--end::Button-->

                                                <!--begin::Button-->
                                                <button type="submit" data-kt-ecommerce-settings-type="submit"
                                                    class="btn btn-primary">
                                                    <span class="indicator-label">
                                                        Save
                                                    </span>
                                                    <span class="indicator-progress">
                                                        Please wait... <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                </button>
                                                <!--end::Button-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Action buttons-->
                                </form>
                                <!--end::Form-->
                            </div>
                            <!--end:::Tab pane-->
                            {{-- KẾT NỐI  --}}
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade" id="kt_ecommerce_settings_localization" role="tabpanel">
                                <!--begin::Card-->
                                <div class="card">
                                    <!--begin::Card header-->
                                    <div class="card-header border-0 pt-6">
                                        <div class="card-title">
                                            <h2 class="fw-bold">Third Party Integrations</h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->

                                    <!--begin::Card body-->
                                    <div class="card-body py-4">
                                        <!--begin::Form-->
                                        <form id="kt_ecommerce_settings_general_localization" class="form"
                                            action="{{ route('admin.settings.update') }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <!-- Xác định tab để controller biết -->
                                            <input type="hidden" name="tab" value="integrations">

                                            <!--begin::Row-->
                                            <div class="row g-9">

                                                <!--begin::Col - SMTP Settings-->
                                                <div class="col-md-6">
                                                    <!--begin::Card-->
                                                    <div class="card card-flush h-md-100">
                                                        <!--begin::Card header-->
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-flex align-items-center">
                                                                    <!--begin::Icon-->
                                                                    <div class="symbol symbol-40px me-3">
                                                                        <div class="symbol-label bg-light-primary">
                                                                            <i
                                                                                class="fas fa-envelope fs-2 text-primary"></i>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Icon-->
                                                                    <h3 class="mb-0">SMTP Configuration</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Card header-->

                                                        <!--begin::Card body-->
                                                        <div class="card-body pt-0">
                                                            <!--begin::Form group - Status-->
                                                            <div class="mb-10">
                                                                <label class="form-label fw-semibold">SMTP Status</label>
                                                                <select class="form-select form-select-solid"
                                                                    name="smtp_status">
                                                                    <option value="1"
                                                                        {{ setting('smtp_status') == 1 ? 'selected' : '' }}>
                                                                        Enabled</option>
                                                                    <option value="0"
                                                                        {{ setting('smtp_status') == 0 ? 'selected' : '' }}>
                                                                        Disabled</option>
                                                                </select>
                                                            </div>
                                                            <!--end::Form group-->

                                                            <!--begin::Form group - Mail Name-->
                                                            <div class="mb-10">
                                                                <label class="form-label">Mail Name</label>
                                                                <input type="text" name="smtp_from_name"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ setting('smtp_from_name') }}"
                                                                    placeholder="VD: Shop bán quần áo" />
                                                            </div>
                                                            <!--end::Form group-->

                                                            <!--begin::Form group - SMTP Host-->
                                                            <div class="mb-10">
                                                                <label class="form-label">SMTP Host</label>
                                                                <input type="text" name="smtp_host"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ setting('smtp_host') }}"
                                                                    placeholder="VD: smtp.gmail.com" />
                                                            </div>
                                                            <!--end::Form group-->

                                                            <!--begin::Row for Port and Encryption-->
                                                            <div class="row mb-10">
                                                                <div class="col-md-6">
                                                                    <label class="form-label">SMTP Port</label>
                                                                    <input type="text" name="smtp_port"
                                                                        class="form-control form-control-solid"
                                                                        value="{{ setting('smtp_port') }}"
                                                                        placeholder="VD: 465, 587" />
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Encryption</label>
                                                                    <input type="text" name="smtp_encryption"
                                                                        class="form-control form-control-solid"
                                                                        value="{{ setting('smtp_encryption') }}"
                                                                        placeholder="VD: ssl/tls" />
                                                                </div>
                                                            </div>
                                                            <!--end::Row-->

                                                            <!--begin::Form group - SMTP Email-->
                                                            <div class="mb-10">
                                                                <label class="form-label">SMTP Email</label>
                                                                <input type="text" name="smtp_email"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ setting('smtp_email') }}"
                                                                    placeholder="VD: yourmail@gmail.com" />
                                                            </div>
                                                            <!--end::Form group-->

                                                            <!--begin::Form group - SMTP Password-->
                                                            <div class="mb-0">
                                                                <label class="form-label">SMTP Password</label>
                                                                <input type="password" name="smtp_password"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ setting('smtp_password') }}"
                                                                    placeholder="Nhập mật khẩu SMTP..." />
                                                            </div>
                                                            <!--end::Form group-->
                                                        </div>
                                                        <!--end::Card body-->
                                                    </div>
                                                    <!--end::Card-->
                                                </div>
                                                <!--end::Col-->

                                                <!--begin::Col - Telegram Settings-->
                                                <div class="col-md-6">
                                                    <!--begin::Card-->
                                                    <div class="card card-flush h-md-100">
                                                        <!--begin::Card header-->
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-flex align-items-center">
                                                                    <!--begin::Icon-->
                                                                    <div class="symbol symbol-40px me-3">
                                                                        <div class="symbol-label bg-light-info">
                                                                            <i class="fab fa-telegram fs-2 text-info"></i>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Icon-->
                                                                    <h3 class="mb-0">Telegram</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Card header-->

                                                        <!--begin::Card body-->
                                                        <div class="card-body pt-0">
                                                            <!--begin::Form group - Status-->
                                                            <div class="mb-10">
                                                                <label class="form-label fw-semibold">Status</label>
                                                                <select class="form-select form-select-solid"
                                                                    name="telegram_status">
                                                                    <option value="1"
                                                                        {{ setting('telegram_status') == 1 ? 'selected' : '' }}>
                                                                        ON</option>
                                                                    <option value="0"
                                                                        {{ setting('telegram_status') == 0 ? 'selected' : '' }}>
                                                                        OFF</option>
                                                                </select>
                                                            </div>
                                                            <!--end::Form group-->

                                                            <!--begin::Form group - Token-->
                                                            <div class="mb-10">
                                                                <label class="form-label">Telegram Token</label>
                                                                <input type="text" name="telegram_token"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ setting('telegram_token') }}"
                                                                    placeholder="Nhập Telegram Bot Token..." />
                                                            </div>
                                                            <!--end::Form group-->

                                                            <!--begin::Form group - Chat ID-->
                                                            <div class="mb-10">
                                                                <label class="form-label">Chat ID</label>
                                                                <input type="text" name="telegram_chat_id"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ setting('telegram_chat_id') }}"
                                                                    placeholder="Nhập Chat ID..." />
                                                            </div>
                                                            <!--end::Form group-->

                                                            <!--begin::Form group - URL-->
                                                            <div class="mb-0">
                                                                <label class="form-label">Telegram URL</label>
                                                                <input type="text" name="telegram_url"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ setting('telegram_url') }}"
                                                                    placeholder="Nhập Telegram API URL..." />
                                                            </div>
                                                            <!--end::Form group-->
                                                        </div>
                                                        <!--end::Card body-->
                                                    </div>
                                                    <!--end::Card-->
                                                </div>
                                                <!--end::Col-->

                                                <!--begin::Col - Google Analytics-->
                                                <div class="col-md-6">
                                                    <!--begin::Card-->
                                                    <div class="card card-flush h-md-100">
                                                        <!--begin::Card header-->
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-flex align-items-center">
                                                                    <!--begin::Icon-->
                                                                    <div class="symbol symbol-40px me-3">
                                                                        <div class="symbol-label bg-light-success">
                                                                            <i class="fab fa-google fs-2 text-success"></i>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Icon-->
                                                                    <h3 class="mb-0">Google Analytics</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Card header-->

                                                        <!--begin::Card body-->
                                                        <div class="card-body pt-0">
                                                            <!--begin::Form group - Status-->
                                                            <div class="mb-10">
                                                                <label class="form-label fw-semibold">Status</label>
                                                                <select class="form-select form-select-solid"
                                                                    name="google_analytics_status">
                                                                    <option value="1"
                                                                        {{ setting('google_analytics_status') == 1 ? 'selected' : '' }}>
                                                                        ON</option>
                                                                    <option value="0"
                                                                        {{ setting('google_analytics_status') == 0 ? 'selected' : '' }}>
                                                                        OFF</option>
                                                                </select>
                                                            </div>
                                                            <!--end::Form group-->

                                                            <!--begin::Form group - Tracking ID-->
                                                            <div class="mb-0">
                                                                <label class="form-label">Tracking ID</label>
                                                                <input type="text" name="google_analytics_id"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ setting('google_analytics_id') }}"
                                                                    placeholder="VD: G-XXXXXXX" />
                                                            </div>
                                                            <!--end::Form group-->
                                                        </div>
                                                        <!--end::Card body-->
                                                    </div>
                                                    <!--end::Card-->
                                                </div>
                                                <!--end::Col-->

                                                <!--begin::Col - Google Ads-->
                                                <div class="col-md-6">
                                                    <!--begin::Card-->
                                                    <div class="card card-flush h-md-100">
                                                        <!--begin::Card header-->
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-flex align-items-center">
                                                                    <!--begin::Icon-->
                                                                    <div class="symbol symbol-40px me-3">
                                                                        <div class="symbol-label bg-light-warning">
                                                                            <i class="fab fa-google fs-2 text-warning"></i>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Icon-->
                                                                    <h3 class="mb-0">Google Ads</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Card header-->

                                                        <!--begin::Card body-->
                                                        <div class="card-body pt-0">
                                                            <!--begin::Form group-->
                                                            <div class="mb-0">
                                                                <label class="form-label">Ads ID</label>
                                                                <input type="text" name="google_ads_id"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ setting('google_ads_id') }}"
                                                                    placeholder="VD: AW-XXXXXXX" />
                                                            </div>
                                                            <!--end::Form group-->
                                                        </div>
                                                        <!--end::Card body-->
                                                    </div>
                                                    <!--end::Card-->
                                                </div>
                                                <!--end::Col-->

                                                <!--begin::Col - ChatGPT-->
                                                <div class="col-md-6">
                                                    <!--begin::Card-->
                                                    <div class="card card-flush h-md-100">
                                                        <!--begin::Card header-->
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-flex align-items-center">
                                                                    <!--begin::Icon-->
                                                                    <div class="symbol symbol-40px me-3">
                                                                        <div class="symbol-label bg-light-dark">
                                                                            <i class="fas fa-robot fs-2 text-dark"></i>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Icon-->
                                                                    <h3 class="mb-0">ChatGPT</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Card header-->

                                                        <!--begin::Card body-->
                                                        <div class="card-body pt-0">
                                                            <!--begin::Form group-->
                                                            <div class="mb-0">
                                                                <label class="form-label">API Key</label>
                                                                <input type="text" name="chatgpt_api_key"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ setting('chatgpt_api_key') }}"
                                                                    placeholder="Nhập API Key của OpenAI..." />
                                                            </div>
                                                            <!--end::Form group-->
                                                        </div>
                                                        <!--end::Card body-->
                                                    </div>
                                                    <!--end::Card-->
                                                </div>
                                                <!--begin::Col - ChatGPT-->
                                                <div class="col-md-6">
                                                    <!--begin::Card-->
                                                    <div class="card card-flush h-md-100">
                                                        <!--begin::Card header-->
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-flex align-items-center">
                                                                    <!--begin::Icon-->
                                                                    <div class="symbol symbol-40px me-3">
                                                                        <div class="symbol-label bg-light-dark">
                                                                            <i class="fas fa-robot fs-2 text-dark"></i>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Icon-->
                                                                    <h3 class="mb-0">Gemini</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Card header-->

                                                        <!--begin::Card body-->
                                                        <div class="card-body pt-0">
                                                            <!--begin::Form group-->
                                                            <div class="mb-0">
                                                                <label class="form-label">API Key</label>
                                                                <input type="text" name="gemini_api_key"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ setting('gemini_api_key') }}"
                                                                    placeholder="Nhập API Key của OpenAI..." />
                                                            </div>
                                                            <!--end::Form group-->
                                                        </div>
                                                        <!--end::Card body-->
                                                    </div>
                                                    <!--end::Card-->
                                                </div>
                                                <!--end::Col-->

                                                <!--begin::Col - Gmail Check-->
                                                <div class="col-md-6">
                                                    <!--begin::Card-->
                                                    <div class="card card-flush h-md-100">
                                                        <!--begin::Card header-->
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-flex align-items-center">
                                                                    <!--begin::Icon-->
                                                                    <div class="symbol symbol-40px me-3">
                                                                        <div class="symbol-label bg-light-danger">
                                                                            <i
                                                                                class="fas fa-envelope fs-2 text-danger"></i>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Icon-->
                                                                    <h3 class="mb-0">Gmail Check</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Card header-->

                                                        <!--begin::Card body-->
                                                        <div class="card-body pt-0">
                                                            <!--begin::Form group-->
                                                            <div class="mb-0">
                                                                <label class="form-label">Email</label>
                                                                <input type="text" name="gmail_check"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ setting('gmail_check') }}"
                                                                    placeholder="VD: check@gmail.com" />
                                                            </div>
                                                            <!--end::Form group-->
                                                        </div>
                                                        <!--end::Card body-->
                                                    </div>
                                                    <!--end::Card-->
                                                </div>
                                                <!--end::Col-->

                                                <!--begin::Col - Instagram Check-->
                                                <div class="col-md-6">
                                                    <!--begin::Card-->
                                                    <div class="card card-flush h-md-100">
                                                        <!--begin::Card header-->
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <div class="d-flex align-items-center">
                                                                    <!--begin::Icon-->
                                                                    <div class="symbol symbol-40px me-3">
                                                                        <div class="symbol-label"
                                                                            style="background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);">
                                                                            <i
                                                                                class="fab fa-instagram fs-2 text-white"></i>
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Icon-->
                                                                    <h3 class="mb-0">Instagram Check</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Card header-->

                                                        <!--begin::Card body-->
                                                        <div class="card-body pt-0">
                                                            <!--begin::Form group-->
                                                            <div class="mb-0">
                                                                <label class="form-label">Username</label>
                                                                <input type="text" name="instagram_check"
                                                                    class="form-control form-control-solid"
                                                                    value="{{ setting('instagram_check') }}"
                                                                    placeholder="VD: @username" />
                                                            </div>
                                                            <!--end::Form group-->
                                                        </div>
                                                        <!--end::Card body-->
                                                    </div>
                                                    <!--end::Card-->
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Row-->

                                            <!--begin::Actions-->
                                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                                <button type="reset" class="btn btn-light me-3">Cancel</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <span class="indicator-label">Save</span>
                                                    <span class="indicator-progress">Please wait...
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                </button>
                                            </div>
                                            <!--end::Actions-->
                                        </form>
                                        <!--end::Form-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Card-->
                            </div>
                            <!--end:::Tab pane-->
                            <!--begin:::Tab pane Telegram Notifications-->
                            <div class="tab-pane fade" id="kt_ecommerce_settings_notifications" role="tabpanel">
                                <!--begin::Form-->
                                <form action="{{ route('admin.settings.update') }}" method="POST" class="form">
                                    @csrf
                                    @method('PUT')

                                    <!-- Xác định tab -->
                                    <input type="hidden" name="tab" value="notifications">

                                    <!--begin::Heading-->
                                    <div class="row mb-10">
                                        <div class="col-md-12">
                                            <div class="d-flex align-items-center mb-5">
                                                <div class="symbol symbol-45px me-5">
                                                    <div class="symbol-label bg-light-primary">
                                                        <i class="fab fa-telegram text-primary fs-2x"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <h2 class="mb-1">Cài đặt thông báo Telegram</h2>
                                                    <span class="text-muted fs-6">Tùy chỉnh các mẫu thông báo được gửi qua
                                                        Telegram</span>
                                                </div>
                                            </div>

                                            <!--begin::Alert-->
                                            <div class="alert alert-primary d-flex align-items-center p-5 mb-10">
                                                <i class="fa-solid fa-lightbulb fs-2hx text-primary me-4"></i>
                                                <div class="d-flex flex-column">
                                                    <h4 class="mb-1 text-primary">Hướng dẫn sử dụng</h4>
                                                    <span>• Để mặc định nếu bạn không có nhu cầu tùy chỉnh<br>
                                                        • Xóa toàn bộ nội dung trong ô nếu không muốn bật thông báo<br>
                                                        • Sử dụng các biến được cung cấp để tùy chỉnh thông báo</span>
                                                </div>
                                            </div>
                                            <div
                                                class="alert alert-dismissible bg-light-info border border-info border-3 border-dashed d-flex flex-column flex-sm-row align-items-center justify-content-center p-5 ">
                                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                                    <span>
                                                        <i class="fa-solid fa-bell"></i> Vui lòng thực hiện CRON JOB liên
                                                        kết:
                                                        <a class="text-primary" href="/cron/check-notification-telegram"
                                                            target="_blank">TELEGRAM
                                                            NOTICE</a>
                                                        1 phút 1 lần hoặc nhanh hơn để hệ thống xử lý thông báo telegram.
                                                    </span>
                                                </div>
                                                <!--end::Alert-->
                                            </div>
                                        </div>
                                        <div class="row g-6">

                                            <!--begin::Notification Card 2-->
                                            <div class="col-12">
                                                <div class="card card-flush border-0 shadow-sm">
                                                    <div class="card-header bg-light-danger">
                                                        <div class="card-title">
                                                            <i class="fa-solid fa-box text-danger fs-3 me-3"></i>
                                                            <span class="fw-bold fs-4">Thông báo số lượng hàng trong kho
                                                                thấp</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-6">
                                                        <!--begin::Input group-->
                                                        <div class="mb-6">
                                                            <label class="form-label fw-semibold fs-6 text-gray-800">Nội
                                                                dung
                                                                thông báo:</label>
                                                            <textarea class="form-control form-control-solid" rows="7" name="telegram_low_stock_template"
                                                                placeholder="Nhập nội dung số lượng hàng trong kho thấp...">{{ setting('telegram_low_stock_template') }}</textarea>
                                                        </div>
                                                        <!--end::Input group-->

                                                        <!--begin::Variables-->
                                                        <div class="bg-light-info rounded p-5">
                                                            <h6 class="text-info fw-bold mb-4">
                                                                <i class="fa-solid fa-code me-2"></i>Biến có thể sử dụng:
                                                            </h6>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-success me-2">{product}</span>
                                                                        <span class="text-muted fs-7">Tên sản phẩm</span>
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-success me-2">{stock}</span>
                                                                        <span class="text-muted fs-7">Số lượng tồn
                                                                            kho</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-success me-2">{time}</span>
                                                                        <span class="text-muted fs-7">Thời gian</span>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Variables-->
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Notification Card 2-->

                                            <!--begin::Notification Card 3-->
                                            <div class="col-12">
                                                <div class="card card-flush border-0 shadow-sm">
                                                    <div class="card-header bg-light-warning">
                                                        <div class="card-title">
                                                            <i class="fa-solid fa-user-shield text-warning fs-3 me-3"></i>
                                                            <span class="fw-bold fs-4">Thông báo hành động</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-6">
                                                        <!--begin::Input group-->
                                                        <div class="mb-6">
                                                            <label class="form-label fw-semibold fs-6 text-gray-800">Nội
                                                                dung
                                                                thông báo:</label>
                                                            <textarea class="form-control form-control-solid" rows="4" name="noti_action"
                                                                placeholder="Nhập nội dung thông báo hành động...">[{time}] 
                                            - <b>Username</b>: <code>{username}</code>
                                            - <b>Action</b>: <code>{action}</code>
                                            - <b>IP</b>: <code>{ip}</code></textarea>
                                                        </div>
                                                        <!--end::Input group-->

                                                        <!--begin::Variables-->
                                                        <div class="bg-light-info rounded p-5">
                                                            <h6 class="text-info fw-bold mb-4">
                                                                <i class="fa-solid fa-code me-2"></i>Biến có thể sử dụng:
                                                            </h6>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-warning me-2">{domain}</span>
                                                                        <span class="text-muted fs-7">Tên website</span>
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-warning me-2">{username}</span>
                                                                        <span class="text-muted fs-7">Tên thành viên</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-warning me-2">{action}</span>
                                                                        <span class="text-muted fs-7">Hành động của thành
                                                                            viên</span>
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-warning me-2">{ip}</span>
                                                                        <span class="text-muted fs-7">Địa chỉ IP của thành
                                                                            viên</span>
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-warning me-2">{time}</span>
                                                                        <span class="text-muted fs-7">Thời gian</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Variables-->
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Notification Card 3-->

                                            <!--begin::Notification Card 4-->
                                            <div class="col-12">
                                                <div class="card card-flush border-0 shadow-sm">
                                                    <div class="card-header bg-light-danger">
                                                        <div class="card-title">
                                                            <i
                                                                class="fa-solid fa-hand-holding-dollar text-danger fs-3 me-3"></i>
                                                            <span class="fw-bold fs-4">Thông báo rút số dư hoa hồng</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-6">
                                                        <!--begin::Input group-->
                                                        <div class="mb-6">
                                                            <label class="form-label fw-semibold fs-6 text-gray-800">Nội
                                                                dung
                                                                thông báo:</label>
                                                            <textarea class="form-control form-control-solid" rows="5" name="noti_affiliate_withdraw"
                                                                placeholder="Nhập nội dung thông báo rút hoa hồng...">[{time}] 
                                    - <b>Username</b>: <code>{username}</code>
                                    - <b>Action</b>: <code>Tạo lệnh rút {amount} về ngân hàng {bank} | {account_number} | {account_name}</code>
                                    - <b>IP</b>: <code>{ip}</code></textarea>
                                                        </div>
                                                        <!--end::Input group-->

                                                        <!--begin::Variables-->
                                                        <div class="bg-light-info rounded p-5">
                                                            <h6 class="text-info fw-bold mb-4">
                                                                <i class="fa-solid fa-code me-2"></i>Biến có thể sử dụng:
                                                            </h6>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-danger me-2">{domain}</span>
                                                                        <span class="text-muted fs-7">Tên website</span>
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-danger me-2">{username}</span>
                                                                        <span class="text-muted fs-7">Tên thành viên
                                                                            rút</span>
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-danger me-2">{bank}</span>
                                                                        <span class="text-muted fs-7">Tên ngân hàng nhận
                                                                            tiền</span>
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-danger me-2">{account_number}</span>
                                                                        <span class="text-muted fs-7">Số tài khoản nhận
                                                                            tiền</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-danger me-2">{account_name}</span>
                                                                        <span class="text-muted fs-7">Tên chủ tài
                                                                            khoản</span>
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-danger me-2">{amount}</span>
                                                                        <span class="text-muted fs-7">Số dư cần rút</span>
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-danger me-2">{ip}</span>
                                                                        <span class="text-muted fs-7">Địa chỉ IP của thành
                                                                            viên</span>
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        <span
                                                                            class="badge badge-light-danger me-2">{time}</span>
                                                                        <span class="text-muted fs-7">Thời gian</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Variables-->
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Notification Card 4-->
                                        </div>
                                        <div class="row py-5">
                                            <div class="col-12">
                                                <div class="d-flex justify-content-end">
                                                    <!--begin::Button-->
                                                    <button type="reset" class="btn btn-light me-3">
                                                        <i class="fa-solid fa-arrow-rotate-left me-2"></i>Hủy bỏ
                                                    </button>
                                                    <!--end::Button-->

                                                    <!--begin::Button-->
                                                    <button type="submit" class="btn btn-primary">
                                                        <span class="indicator-label">
                                                            <i class="fa-solid fa-floppy-disk me-2"></i>Lưu cài đặt
                                                        </span>
                                                        <span class="indicator-progress">
                                                            Đang lưu...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                        </span>
                                                    </button>
                                                    <!--end::Button-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Action buttons-->
                                </form>
                                <!--end::Form-->
                            </div>
                            <!--end:::Tab pane Telegram Notifications-->
                            {{-- Cài đặt tồn kho --}}
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade" id="kt_ecommerce_settings_products" role="tabpanel">
                                <!--begin::Form-->
                                <form id="kt_ecommerce_settings_general_products" class="form" method="POST"
                                    action="{{ route('admin.settings.update') }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="tab" value="products">

                                    <!--begin::Heading-->
                                    <div class="row mb-7">
                                        <div class="col-md-9 offset-md-3">
                                            <h2>Cài đặt tồn kho</h2>
                                        </div>
                                    </div>
                                    <!--end::Heading-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Category Product Count</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Show the number of products inside the subcategories in the storefront header category menu. Be warned, this will cause an extreme performance hit for stores with a lot of subcategories!">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i></span> </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <div class="d-flex mt-3">
                                                <!--begin::Radio-->
                                                <div class="form-check form-check-custom form-check-solid me-5">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="category_product_count" id="category_product_count_yes"
                                                        checked />
                                                    <label class="form-check-label" for="category_product_count_yes">
                                                        Yes
                                                    </label>
                                                </div>

                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="category_product_count" id="category_product_count_no" />
                                                    <label class="form-check-label" for="category_product_count_no">
                                                        No
                                                    </label>
                                                </div>
                                                <!--end::Radio-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-16">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="required">Cảnh báo kho hàng</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Kho hàng đến số lượng này sẽ báo cáo cho admin biết gần hết">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i></span> </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <!--begin::Input-->
                                            <input type="number" class="form-control form-control-solid"
                                                name="low_stock_alert"
                                                value="{{ $settings['low_stock_alert']->value ?? 10 }}" />

                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Heading-->
                                    <div class="row mb-7">
                                        <div class="col-md-9 offset-md-3">
                                            <h2>Reviews Settings</h2>
                                        </div>
                                    </div>
                                    <!--end::Heading-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Allow Reviews</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Enable/disable review entries for registered customers.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i></span> </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <div class="d-flex mt-3">
                                                <!--begin::Radio-->
                                                <div class="form-check form-check-custom form-check-solid me-5">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="allow_reviews" id="allow_reviews_yes" checked />
                                                    <label class="form-check-label" for="allow_reviews_yes">
                                                        Yes
                                                    </label>
                                                </div>

                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="allow_reviews" id="allow_reviews_no" />
                                                    <label class="form-check-label" for="allow_reviews_no">
                                                        No
                                                    </label>
                                                </div>
                                                <!--end::Radio-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-16">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Allow Guest Reviews</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Enable/disable review entries for public guest customers">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i></span> </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <div class="d-flex mt-3">
                                                <!--begin::Radio-->
                                                <div class="form-check form-check-custom form-check-solid me-5">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="allow_guest_reviews" id="allow_guest_reviews_yes" />
                                                    <label class="form-check-label" for="allow_guest_reviews_yes">
                                                        Yes
                                                    </label>
                                                </div>

                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="allow_guest_reviews" id="allow_guest_reviews_no" checked />
                                                    <label class="form-check-label" for="allow_guest_reviews_no">
                                                        No
                                                    </label>
                                                </div>
                                                <!--end::Radio-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Heading-->
                                    <div class="row mb-7">
                                        <div class="col-md-9 offset-md-3">
                                            <h2>Vouchers Settings</h2>
                                        </div>
                                    </div>
                                    <!--end::Heading-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="required">Minimum Vouchers</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Minimum number of vouchers customers can attach to an order">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i></span> </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-solid"
                                                name="products_min_voucher" value="1" />
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-16">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="required">Maximum Vouchers</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Maximum number of vouchers customers can attach to an order">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i></span> </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-solid"
                                                name="products_max_voucher" value="10" />
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Heading-->
                                    <div class="row mb-7">
                                        <div class="col-md-9 offset-md-3">
                                            <h2>Tax Settings</h2>
                                        </div>
                                    </div>
                                    <!--end::Heading-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Display Prices with Tax</span>
                                            </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <div class="d-flex mt-3">
                                                <!--begin::Radio-->
                                                <div class="form-check form-check-custom form-check-solid me-5">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="product_tax" id="product_tax_yes" checked />
                                                    <label class="form-check-label" for="product_tax_yes">
                                                        Yes
                                                    </label>
                                                </div>

                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="product_tax" id="product_tax_no" />
                                                    <label class="form-check-label" for="product_tax_no">
                                                        No
                                                    </label>
                                                </div>
                                                <!--end::Radio-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="required">Default Tax Rate</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Determines the tax percentage (%) applied to orders">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i></span> </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-solid"
                                                name="products_tax_rate" value="15%" />
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Action buttons-->
                                    <div class="row py-5">
                                        <div class="col-md-9 offset-md-3">
                                            <div class="d-flex">
                                                <!--begin::Button-->
                                                <button type="reset" data-kt-ecommerce-settings-type="cancel"
                                                    class="btn btn-light me-3">
                                                    Cancel
                                                </button>
                                                <!--end::Button-->

                                                <!--begin::Button-->
                                                <button type="submit" data-kt-ecommerce-settings-type="submit"
                                                    class="btn btn-primary">
                                                    <span class="indicator-label">
                                                        Save
                                                    </span>
                                                    <span class="indicator-progress">
                                                        Please wait... <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                </button>
                                                <!--end::Button-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Action buttons-->
                                </form>
                                <!--end::Form-->
                            </div>
                            <!--end:::Tab pane-->

                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade" id="kt_ecommerce_settings_customers" role="tabpanel">

                                <!--begin::Form-->
                                <form id="kt_ecommerce_settings_general_customers" class="form" action="#">
                                    <!--begin::Heading-->
                                    <div class="row mb-7">
                                        <div class="col-md-9 offset-md-3">
                                            <h2>Customers Settings</h2>
                                        </div>
                                    </div>
                                    <!--end::Heading-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Customers Online</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Enable/disable tracking customers online status.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i></span> </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <div class="d-flex mt-3">
                                                <!--begin::Radio-->
                                                <div class="form-check form-check-custom form-check-solid me-5">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="customers_online" id="customers_online_yes" checked />
                                                    <label class="form-check-label" for="customers_online_yes">
                                                        Yes
                                                    </label>
                                                </div>

                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="customers_online" id="customers_online_no" />
                                                    <label class="form-check-label" for="customers_online_no">
                                                        No
                                                    </label>
                                                </div>
                                                <!--end::Radio-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Customers Activity</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Enable/disable tracking customers activity.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i></span> </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <div class="d-flex mt-3">
                                                <!--begin::Radio-->
                                                <div class="form-check form-check-custom form-check-solid me-5">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="customers_activity" id="customers_activity_yes" checked />
                                                    <label class="form-check-label" for="customers_activity_yes">
                                                        Yes
                                                    </label>
                                                </div>

                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="customers_activity" id="customers_activity_no" />
                                                    <label class="form-check-label" for="customers_activity_no">
                                                        No
                                                    </label>
                                                </div>
                                                <!--end::Radio-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Customer Searches</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Enable/disable logging customers search keywords.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i></span> </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <div class="d-flex mt-3">
                                                <!--begin::Radio-->
                                                <div class="form-check form-check-custom form-check-solid me-5">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="customers_searches" id="customers_searches_yes" checked />
                                                    <label class="form-check-label" for="customers_searches_yes">
                                                        Yes
                                                    </label>
                                                </div>

                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="customers_searches" id="customers_searches_no" />
                                                    <label class="form-check-label" for="customers_searches_no">
                                                        No
                                                    </label>
                                                </div>
                                                <!--end::Radio-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Allow Guest Checkout</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Enable/disable guest customers to checkout.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i></span> </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <div class="d-flex mt-3">
                                                <!--begin::Radio-->
                                                <div class="form-check form-check-custom form-check-solid me-5">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="customers_guest_checkout"
                                                        id="customers_guest_checkout_yes" />
                                                    <label class="form-check-label" for="customers_guest_checkout_yes">
                                                        Yes
                                                    </label>
                                                </div>

                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="customers_guest_checkout" id="customers_guest_checkout_no"
                                                        checked />
                                                    <label class="form-check-label" for="customers_guest_checkout_no">
                                                        No
                                                    </label>
                                                </div>
                                                <!--end::Radio-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span>Login Display Prices</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Only show prices when customers log in.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i></span> </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <div class="d-flex mt-3">
                                                <!--begin::Radio-->
                                                <div class="form-check form-check-custom form-check-solid me-5">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="customers_login_prices" id="customers_login_prices_yes" />
                                                    <label class="form-check-label" for="customers_login_prices_yes">
                                                        Yes
                                                    </label>
                                                </div>

                                                <div class="form-check form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="radio" value=""
                                                        name="customers_login_prices" id="customers_login_prices_no"
                                                        checked />
                                                    <label class="form-check-label" for="customers_login_prices_no">
                                                        No
                                                    </label>
                                                </div>
                                                <!--end::Radio-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <div class="col-md-3 text-md-end">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold form-label mt-3">
                                                <span class="required">Max Login Attempts</span>


                                                <span class="ms-1" data-bs-toggle="tooltip"
                                                    title="Set the max number of login attempts before the customer account is locked for 1 hour.">
                                                    <i class="fa-solid fa-circle-info text-gray-500 fs-6"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span></i></span> </label>
                                            <!--end::Label-->
                                        </div>

                                        <div class="col-md-9">
                                            <!--begin::Input-->
                                            <input type="text" class="form-control form-control-solid"
                                                name="customer_login_attempts" value="" />
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Action buttons-->
                                    <div class="row py-5">
                                        <div class="col-md-9 offset-md-3">
                                            <div class="d-flex">
                                                <!--begin::Button-->
                                                <button type="reset" data-kt-ecommerce-settings-type="cancel"
                                                    class="btn btn-light me-3">
                                                    Cancel
                                                </button>
                                                <!--end::Button-->

                                                <!--begin::Button-->
                                                <button type="submit" data-kt-ecommerce-settings-type="submit"
                                                    class="btn btn-primary">
                                                    <span class="indicator-label">
                                                        Save
                                                    </span>
                                                    <span class="indicator-progress">
                                                        Please wait... <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                </button>
                                                <!--end::Button-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Action buttons-->
                                </form>
                                <!--end::Form-->
                            </div>
                            <!--end:::Tab pane-->
                        </div>
                        <!--end:::Tab content-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

    </div>
    <!--end::Content wrapper-->
@endsection
@section('js')

    <script src="https://unpkg.com/@yaireo/tagify"></script>
    <script>
        new Tagify(document.querySelector('[name="keywords"]'));
    </script>
    <!-- CKEditor 5 CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#return_policy'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
