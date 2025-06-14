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
                        Thêm ngân hàng
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
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            Cấu hình</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            Thêm</li>

                    </ul>
                    <!--begin::Breadcrumb-->

                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('admin.bank.config') }}" class="btn btn-sm fw-bold btn-danger">
                        <i class="fa-solid fa-arrow-left fs-6"><span class="path1"></span><span class="path2"></span><span
                                class="path3"></span><span class="path4"></span></i>
                        Quay lại
                    </a>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->

        <div id="kt_app_content_container" class="app-container  container-xxl ">
            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <h3
                                class="page-heading d-flex text-gray-900 fw-bold fs-5 flex-column justify-content-center my-0">
                                DANH SÁCH NGÂN HÀNG

                            </h3>
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->

                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

                        <!--begin::Add product-->
                        <a href="#" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-plus fs-5"><span class="path1"></span><span class="path2"></span><span
                                    class="path3"></span><span class="path4"></span></i>
                            Thêm ngân hàng
                        </a>
                        <!--end::Add product-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">

                    <!--begin::Table-->
                    <div id="kt_ecommerce_products_table_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">
                        <div id="" class="table-responsive">
                            <table class="align-middle table text-nowrap table-striped table-hover table-bordered dataTable no-footer"
                              >
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">

                                        <th class=" min-w-70px dt-type-numeric dt-orderable-asc dt-orderable-desc dt-ordering-desc"
                                            data-dt-column="2" rowspan="1" colspan="1"
                                            aria-label="SKU: Activate to remove sorting" tabindex="0"
                                            aria-sort="descending"><span class="dt-column-title"
                                                role="button">#</span><span class="dt-column-order"></span></th>
                                        <th class=" min-w-70px dt-type-numeric dt-orderable-asc dt-orderable-desc"
                                            data-dt-column="3" rowspan="1" colspan="1"
                                            aria-label="Qty: Activate to sort" tabindex="0"><span class="dt-column-title"
                                                role="button">Ngân hàng</span><span class="dt-column-order"></span></th>
                                        <th class=" min-w-100px dt-type-numeric dt-orderable-asc dt-orderable-desc"
                                            data-dt-column="4" rowspan="1" colspan="1"
                                            aria-label="Price: Activate to sort" tabindex="0"><span
                                                class="dt-column-title" role="button">Số tài khoản</span><span
                                                class="dt-column-order"></span></th>
                                        <th class=" min-w-100px dt-orderable-asc dt-orderable-desc" data-dt-column="5"
                                            rowspan="1" colspan="1" aria-label="Rating: Activate to sort"
                                            tabindex="0"><span class="dt-column-title" role="button">Chủ tài
                                                khoản</span><span class="dt-column-order"></span></th>
                                        <th class=" min-w-100px dt-orderable-asc dt-orderable-desc" data-dt-column="6"
                                            rowspan="1" colspan="1" aria-label="Status: Activate to sort"
                                            tabindex="0"><span class="dt-column-title" role="button">Trạng
                                                thái</span><span class="dt-column-order"></span></th>
                                        <th class=" min-w-70px dt-orderable-none" data-dt-column="7" rowspan="1"
                                            colspan="1" aria-label="Actions"><span class="dt-column-title">Thao
                                                tác</span><span class="dt-column-order"></span></th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    <tr>
                                        <td class=" pe-0 dt-type-numeric sorting_1">
                                            <span class="fw-bold">0</span>
                                        </td>
                                        <td class=" pe-0 dt-type-numeric" data-order="50">
                                            <span class="fw-bold ms-3">MB</span>
                                        </td>
                                        <td class=" pe-0 dt-type-numeric">531668888</td>
                                        <td class=" pe-0 dt-type-numeric">TRAN MINH DUC </td>
                                        <td class=" pe-0" data-order="Scheduled">
                                            <!--begin::Badges-->
                                            <div class="badge badge-light-primary">Hiển thị</div>
                                            <!--end::Badges-->
                                        </td>
                                        {{-- <td class="">
                                            <a href="#"
                                                class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                Actions
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i> </a>
                                            <!--begin::Menu-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                data-kt-menu="true">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="/metronic8/demo1/apps/ecommerce/catalog/edit-product.html"
                                                        class="menu-link px-3">
                                                        Edit
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3"
                                                        data-kt-ecommerce-product-filter="delete_row">
                                                        Delete
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu-->
                                        </td> --}}
                                        <td><a aria-label="" href="" style="color:white;"
                                                class="btn btn-info btn-sm btn-icon-left m-b-10" type="button">
                                                <i class="fas fa-edit mr-1"></i><span class=""> Edit</span>
                                            </a>
                                            <button style="color:white;"
                                                class="btn btn-danger btn-sm btn-icon-left m-b-10" type="button">
                                                <i class="fas fa-trash mr-1"></i><span class=""> Delete</span>
                                            </button>
                                        </td>
                                    </tr>

                                </tbody>
                                <tfoot></tfoot>
                            </table>
                        </div>
                        <div id="" class="row">
                            <div id=""
                                class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                                <div><select name="kt_ecommerce_products_table_length"
                                        aria-controls="kt_ecommerce_products_table"
                                        class="form-select form-select-solid form-select-sm" id="dt-length-0">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select><label for="dt-length-0"></label></div>
                            </div>
                            <div id=""
                                class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="dt-paging paging_simple_numbers">
                                    <nav aria-label="pagination">
                                        <ul class="pagination">
                                            <li class="dt-paging-button page-item disabled"><button
                                                    class="page-link previous" role="link" type="button"
                                                    aria-controls="kt_ecommerce_products_table" aria-disabled="true"
                                                    aria-label="Previous" data-dt-idx="previous" tabindex="-1"><i
                                                        class="previous"></i></button></li>
                                            <li class="dt-paging-button page-item active"><button class="page-link"
                                                    role="link" type="button"
                                                    aria-controls="kt_ecommerce_products_table" aria-current="page"
                                                    data-dt-idx="0">1</button></li>
                                            <li class="dt-paging-button page-item"><button class="page-link"
                                                    role="link" type="button"
                                                    aria-controls="kt_ecommerce_products_table" data-dt-idx="1">2</button>
                                            </li>
                                            <li class="dt-paging-button page-item"><button class="page-link"
                                                    role="link" type="button"
                                                    aria-controls="kt_ecommerce_products_table" data-dt-idx="2">3</button>
                                            </li>
                                            <li class="dt-paging-button page-item"><button class="page-link"
                                                    role="link" type="button"
                                                    aria-controls="kt_ecommerce_products_table" data-dt-idx="3">4</button>
                                            </li>
                                            <li class="dt-paging-button page-item"><button class="page-link"
                                                    role="link" type="button"
                                                    aria-controls="kt_ecommerce_products_table" data-dt-idx="4">5</button>
                                            </li>
                                            <li class="dt-paging-button page-item"><button class="page-link next"
                                                    role="link" type="button"
                                                    aria-controls="kt_ecommerce_products_table" aria-label="Next"
                                                    data-dt-idx="next"><i class="next"></i></button></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Products-->
        </div>

    </div>
    <!--end::Content wrapper-->
@endsection
