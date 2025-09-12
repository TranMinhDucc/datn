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
                            <!--end:::Tab item-->

                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-5" data-bs-toggle="tab"
                                    href="#kt_ecommerce_settings_localization">
                                    <i class="fa-solid fa-plug fs-2 me-2"><span class="path1"></span><span
                                            class="path2"></span></i> Kết nối
                                </a>
                            </li>
                            <!--end:::Tab item-->

                            <!--begin:::Tab item-->
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
                                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6"><span
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
                                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6"><span
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
                                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6"><span
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

                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade" id="kt_ecommerce_settings_localization" role="tabpanel">
                                <!--begin::Form-->

                                <form id="kt_ecommerce_settings_general_localization" class="form" action="#">
                                    <!--begin::Heading-->
                                    <div class="col-md-6">
                                        <table class="table table-bordered table-striped table-hover mb-3">
                                            <thead class="table-dark text-center">
                                                <tr>
                                                    <th colspan="2">
                                                        <img src="https://sieustore.com/assets/img/icon-smtp.png"
                                                            width="20px" class="me-1">
                                                        SMTP
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Bật/Tắt SMTP -->
                                                <tr>
                                                    <td>
                                                        <i class="fa fa-toggle-on text-success"></i>
                                                        SMTP Mail
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="smtp_status">
                                                            <option value="1" selected="">
                                                                ON
                                                            </option>
                                                            <option value="0">
                                                                OFF
                                                            </option>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <!-- SMTP Host -->
                                                <tr>
                                                    <td>
                                                        <i class="fas fa-server text-primary"></i>
                                                        SMTP Host
                                                    </td>
                                                    <td>
                                                        <input type="text" name="smtp_host" class="form-control"
                                                            placeholder="VD: smtp.gmail.com" value="smtp.gmail.com">
                                                    </td>
                                                </tr>

                                                <!-- SMTP Encryption -->
                                                <tr>
                                                    <td>
                                                        <i class="fas fa-shield-alt text-warning"></i>
                                                        SMTP Encryption
                                                    </td>
                                                    <td>
                                                        <input type="text" name="smtp_encryption" class="form-control"
                                                            placeholder="VD: ssl/tls" value="tls">
                                                    </td>
                                                </tr>

                                                <!-- SMTP Port -->
                                                <tr>
                                                    <td>
                                                        <i class="fas fa-network-wired text-info"></i>
                                                        SMTP Port
                                                    </td>
                                                    <td>
                                                        <input type="text" name="smtp_port" class="form-control"
                                                            placeholder="VD: 465, 587" value="587">
                                                    </td>
                                                </tr>

                                                <!-- SMTP Email -->
                                                <tr>
                                                    <td>
                                                        <i class="fa fa-envelope text-danger"></i>
                                                        SMTP Email
                                                    </td>
                                                    <td>
                                                        <input type="text" name="smtp_email" class="form-control"
                                                            placeholder="VD: yourmail@gmail.com"
                                                            value="sieustoremmo@gmail.com">
                                                    </td>
                                                </tr>

                                                <!-- SMTP Password -->
                                                <tr>
                                                    <td>
                                                        <i class="fas fa-key text-secondary"></i>
                                                        SMTP Password
                                                    </td>
                                                    <td>
                                                        <input type="text" name="smtp_password" class="form-control"
                                                            placeholder="Nhập mật khẩu SMTP..."
                                                            value="t d l o f r y y n y h o n y i n">
                                                        <small class="text-muted">

                                                            Hướng dẫn tích hợp SMTP Gmail miễn phí tại <a
                                                                href="https://help.cmsnt.co/huong-dan/huong-dan-cau-hinh-smtp-vao-website-shopclone7/"
                                                                target="_blank" class="text-primary">đây</a>, hoặc sử dụng
                                                            Email theo tên miền tại <a href="https://ntlink.co/TMtoW"
                                                                target="_blank" class="text-primary">đây</a>.

                                                        </small>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row mb-7">
                                        <div class="col-md-9 offset-md-3">
                                            <h2>Localization Settings</h2>
                                        </div>
                                    </div>
                                    <!--end::Heading-->

                                    <!--begin::Input group-->

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
                                    {{-- <div class="row fv-row mb-7">
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
                                    </div> --}}
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

@endsection
