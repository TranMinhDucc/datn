@extends('layouts.admin')

@section('title', 'Recharge Bank')
@section('content')
    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">

            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">



                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Ngân hàng
                    </h1>
                    <!--end::Title-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="/metronic8/demo1/index.html" class="text-muted text-hover-primary">
                                Nạp tiền </a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->

                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            Ngân hàng </li>
                        <!--end::Item-->

                    </ul>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <!--begin::Breadcrumb-->

                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('admin.bank.config') }}" class="btn btn-sm fw-bold btn-primary">
                        <i class="fa-solid fa-gear fs-6"><span class="path1"></span><span class="path2"></span><span
                                class="path3"></span><span class="path4"></span></i>
                        Cấu hình
                    </a>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content  flex-column-fluid ">


            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-xxl ">
                <div class="d-flex flex-wrap gap-4 justify-content-between mb-10">

                    <!--begin::Col-->
                    <div class="col">
                        <!--begin::Card widget 2-->
                        <div class="card">
                            <!--begin::Body-->
                            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <i class="fa-solid fa-money-bills fs-2hx text-gray-900"><span
                                            class="path1"></span><span class="path2"></span></i>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">327</span>
                                    <!--end::Number-->

                                </div>
                                <!--end::Section-->

                                <!--begin::Badge-->
                                <span class="badge badge-light-success fs-base">
                                    Toàn thời gian
                                </span>
                                <!--end::Badge-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->


                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col">
                        <!--begin::Card widget 2-->
                        <div class="card">
                            <!--begin::Body-->
                            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <i class="fa-solid fa-money-bills fs-2hx text-gray-900"><span
                                            class="path1"></span><span class="path2"></span></i>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">327</span>
                                    <!--end::Number-->

                                </div>
                                <!--end::Section-->

                                <!--begin::Badge-->
                                <span class="badge badge-light-success fs-base">
                                    Toàn thời gian
                                </span>
                                <!--end::Badge-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->


                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col">
                        <!--begin::Card widget 2-->
                        <div class="card">
                            <!--begin::Body-->
                            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <i class="fa-solid fa-money-bills fs-2hx text-gray-900"><span
                                            class="path1"></span><span class="path2"></span></i>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">327</span>
                                    <!--end::Number-->

                                </div>
                                <!--end::Section-->

                                <!--begin::Badge-->
                                <span class="badge badge-light-success fs-base">
                                    Toàn thời gian
                                </span>
                                <!--end::Badge-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->


                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col">
                        <!--begin::Card widget 2-->
                        <div class="card">
                            <!--begin::Body-->
                            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <i class="fa-solid fa-money-bills fs-2hx text-gray-900"><span
                                            class="path1"></span><span class="path2"></span></i>
                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">327</span>
                                    <!--end::Number-->

                                </div>
                                <!--end::Section-->

                                <!--begin::Badge-->
                                <span class="badge badge-light-success fs-base">
                                    Toàn thời gian
                                </span>
                                <!--end::Badge-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->


                    </div>
                    <!--end::Col-->


                </div>

                <!--begin::Category-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <!--begin::Card title-->
                        <h3 class="page-heading d-flex text-gray-900 fw-bold fs-5 flex-column justify-content-center my-0">
                            LỊCH SỬ NẠP TIỀN TỰ ĐỘNG
                        </h3>
                        <!--end::Card title-->
                        <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                            <div class="row row-cols-lg-auto g-3 mb-3">
                                <input type="hidden" name="module" value="admin">
                                <input type="hidden" name="action" value="recharge-bank">
                                <div class="col-md-3 col-6">
                                    <input class="form-control form-control-sm" value="" name="user_id"
                                        placeholder="Tìm ID thành viên">
                                </div>
                                <div class="col-md-3 col-6">
                                    <input class="form-control form-control-sm" value="" name="username"
                                        placeholder="Tìm Username">
                                </div>
                                <div class="col-md-3 col-6">
                                    <input class="form-control form-control-sm" value="" name="tid"
                                        placeholder="Mã giao dịch">
                                </div>
                                <div class="col-md-3 col-6">
                                    <input class="form-control form-control-sm" value="" name="method"
                                        placeholder="Ngân hàng">
                                </div>
                                <div class="col-md-3 col-6">
                                    <input class="form-control form-control-sm" value="" name="description"
                                        placeholder="Nội dung chuyển khoản">
                                </div>
                                <div class="col-md-3 col-6">
                                    <input type="date" class="form-control form-control-sm" value=""
                                        name="description" placeholder="Nội dung chuyển khoản">
                                </div>


                            </div>
                            <div class="col-12">
                                <button class="btn btn-sm btn-primary"><i class="fa fa-search"></i>
                                    Search </button>
                                <a class="btn btn-sm btn-danger"
                                    href="https://sieustore.com/?module=admin&amp;action=recharge-bank"><i
                                        class="fa fa-trash"></i>
                                    Clear filter </a>
                            </div>

                        </form>

                        <!--begin::Card toolbar-->
                        {{-- <div class="card-toolbar">
                                <!--begin::Add customer-->
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                    Thêm danh mục mới
                                </a>
                                <!--end::Add customer-->
                            </div> --}}
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">

                        <!--begin::Table-->
                        <div class="table-responsive mb-3">
                            <table class="table text-nowrap table-striped table-hover table-bordered">
                                <thead>
                                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-gray-200 fw-bold ">
                                        <th class="min-w-100px ">Username</th>
                                        <th class="min-w-200px">Thời gian</th>
                                        <th class="min-w-150px text-center">Số tiền nạp</th>
                                        <th class="min-w-150px text-center">Ngân hàng</th>
                                        <th class="min-w-150px text-center">Mã giao dịch</th>
                                        <th class="min-w-150px">Nội dung chuyển khoản</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class=" text-center">QuangAnh [ID 235]</span></td>
                                        <td><span class="">2025-06-10 19:35:21</span></td>
                                        <td class="fw-bold text-center"> 54.000đx</td>
                                        <td class="fw-bold text-center"><span>MB</span></td>
                                        <td class="fw-bold text-center"><span>FT25161206918855</span></td>
                                        <td class="">CTCP DICH VU DI DONG TRUC TUYEN
                                            90376005922-SIEUSTORE235-CHUYEN TIE
                                            N-OQCH94864220-MOMO90376005922MOMO. TU: M SERVICE JSC
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>

                <!--end::Category-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

    </div>
    <!--end::Content wrapper-->
@endsection
