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
                        Cấu hình ngân hàng
                    </h1>
                    <!--end::Title-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-primary">
                            <a href="#" class="text-primary">
                                Nạp tiền </a>
                        </li>

                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-primary">
                            <a href="{{ route('admin.bank.view_payment') }}" class="text-primary">
                                Ngân hàng </a>
                        </li>

                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            Cấu hình</li>

                    </ul>
                    <!--begin::Breadcrumb-->

                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('admin.bank.view_payment') }}" class="btn btn-sm fw-bold btn-danger">
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
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBankModal">
                            <i class="fa-solid fa-plus fs-5"></i> Thêm ngân hàng
                        </button>


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
                            <table
                                class="align-middle table text-nowrap table-striped table-hover table-bordered dataTable no-footer">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">

                                        <th class=" min-w-70px dt-type-numeric dt-orderable-asc dt-orderable-desc dt-ordering-desc"
                                            data-dt-column="2" rowspan="1" colspan="1"
                                            aria-label="SKU: Activate to remove sorting" tabindex="0"
                                            aria-sort="descending"><span class="dt-column-title"
                                                role="button">ID</span><span class="dt-column-order"></span></th>
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
                                    @foreach ($banks as $index => $bank)
                                        <tr>
                                            <td class=" pe-0 dt-type-numeric sorting_1">
                                                <span class="fw-bold">{{ $bank->id }}</span>
                                            </td>
                                            <td class=" pe-0 dt-type-numeric" data-order="50">
                                                <span class="fw-bold ms-3">{{ $bank->short_name }}</span>
                                            </td>
                                            <td class=" pe-0 dt-type-numeric">{{ $bank->account_number }}</td>
                                            <td class=" pe-0 dt-type-numeric">{{ $bank->account_name }}</td>
                                            <td class="pe-0">
                                                @if ($bank->status == 1)
                                                    <div class="badge badge-light-success">Hiển thị</div>
                                                @else
                                                    <div class="badge badge-light-danger">Ẩn</div>
                                                @endif
                                            </td>

                                            <td><a aria-label="" href="{{ route('admin.bank.config_edit', $bank->id) }}"
                                                    style="color:white;" class="btn btn-info btn-sm btn-icon-left m-b-10"
                                                    type="button">
                                                    <i class="fas fa-edit mr-1"></i><span class=""> Edit</span>
                                                </a>
                                                <form action="{{ route('admin.bank.destroy', $bank->id) }}" method="POST"
                                                    style="display: inline-block;"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa ngân hàng này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm btn-icon-left m-b-10">
                                                        <i class="fas fa-trash mr-1"></i><span class="">
                                                            Delete</span>
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot></tfoot>
                            </table>
                            <div class="mt-4">
                                {{ $banks->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                    <!--end::Table-->

                </div>
                <!--end::Card body-->
            </div>
            <!--end::Products-->
        </div>
        <div id="kt_app_content_container" class="app-container  container-xxl py-3 py-lg-6">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <h3
                                class="page-heading d-flex text-gray-900 fw-bold fs-5 flex-column justify-content-center my-0">
                                CẤU HÌNH
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.bank.config_update_two') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-lg-12 col-xl-6">
                                    <div class="row mb-4">
                                        <label class="col-sm-4 col-form-label" for="example-hf-email">Trạng
                                            thái</label>
                                        <div class="col-sm-8">
                                            <select name="bank_status" class="form-control form-control-sm">
                                                <option value="1"
                                                    {{ ($settings['bank_status'] ?? '') == '1' ? 'selected' : '' }}>
                                                    ON</option>
                                                <option value="0"
                                                    {{ ($settings['bank_status'] ?? '') == '0' ? 'selected' : '' }}>
                                                    OFF</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-4 col-form-label" for="example-hf-email">Prefix</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm"
                                                    value="{{ $settings['prefix_autobank'] }}" name="prefix_autobank"
                                                    placeholder="VD: NAPTIEN">
                                                <span class="input-group-text">
                                                    {{ $username }} </span>
                                            </div>
                                            <small>Không được để trống Prefix, Prefix là nội dung nạp tiền vào hệ
                                                thống.</small>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-4 col-form-label" for="example-hf-email">Bảo mật link
                                            CRON</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                    value="{{ $settings['cron_bank_security'] }}"
                                                    name="cron_bank_security" placeholder="Token Webhook API nếu có">

                                            </div>
                                            <small>Bạn không nên công khai route này vì bất cứ ai có thể vào link là hệ
                                                thống tự cộng tiền!</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <div class="row mb-4">
                                        <label class="col-sm-6 col-form-label" for="example-hf-email">Số tiền
                                            nạp tối thiểu</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-sm"
                                                value="{{ $settings['bank_min'] }}" name="bank_min">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-6 col-form-label" for="example-hf-email">Số tiền
                                            nạp tối đa</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-sm"
                                                value="{{ $settings['bank_max'] }}" name="bank_max">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-12">
                                    <div class="row mb-4">
                                        <label class="col-sm-6 col-form-label" for="example-hf-email">Lưu ý nạp
                                            tiền</label>
                                        <div class="col-sm-12">
                                            {{-- box ghi nội dung  --}}

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a type="button" class="btn btn-danger btn-sm" href="{{ route('admin.bank.config') }}"><i
                                    class="fa fa-fw fa-undo me-1"></i>
                                Reload</a>
                            <button type="submit" name="SaveSettings" class="btn btn-success btn-sm">
                                <i class="fa fa-fw fa-save me-1"></i> Save </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!--end::Content wrapper-->
    <!-- Modal Thêm ngân hàng -->
    <div class="modal fade" id="addBankModal" tabindex="-1" aria-labelledby="addBankModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('admin.bank.config_add') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm ngân hàng mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngân hàng *</label>
                            <input type="text" list="bank_list" name="short_name" class="form-control"
                                placeholder="Nhập tên ngân hàng">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hình ảnh *</label>
                            <input type="file" name="image" class="form-control">
                            <small class="text-muted">Khi VietQR không hoạt động, hệ thống sẽ hiện ảnh thay QR</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số tài khoản *</label>
                            <input type="text" name="account_number" class="form-control"
                                placeholder="Nhập số tài khoản">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Chủ tài khoản *</label>
                            <input type="text" name="account_name" class="form-control"
                                placeholder="Nhập tên chủ tài khoản">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password Internet Banking</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Áp dụng cấu hình nạp tiền tự động">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Token</label>
                            <input type="text" name="token" class="form-control"
                                placeholder="Áp dụng cấu hình nạp tiền tự động">
                        </div>

                        <datalist id="bank_list">
                            <option value="THESIEURE">Ví THESIEURE.COM</option>
                            <option value="MOMO">Ví điện tử MOMO</option>
                            <option value="Zalo Pay">Ví điện tử Zalo Pay</option>
                            <option value="ICB">Ngân hàng TMCP Công thương Việt Nam</option>
                            <option value="VCB">Ngân hàng TMCP Ngoại Thương Việt Nam</option>
                            <option value="BIDV">Ngân hàng TMCP Đầu tư và Phát triển Việt Nam</option>
                            <option value="VBA">Ngân hàng Nông nghiệp và Phát triển Nông thôn Việt Nam</option>
                            <option value="OCB">Ngân hàng TMCP Phương Đông</option>
                            <option value="MB">Ngân hàng TMCP Quân đội</option>
                            <option value="TCB">Ngân hàng TMCP Kỹ thương Việt Nam</option>
                            <option value="ACB">Ngân hàng TMCP Á Châu</option>
                            <option value="VPB">Ngân hàng TMCP Việt Nam Thịnh Vượng</option>
                            <option value="TPB">Ngân hàng TMCP Tiên Phong</option>
                            <option value="STB">Ngân hàng TMCP Sài Gòn Thương Tín</option>
                            <option value="HDB">Ngân hàng TMCP Phát triển Thành phố Hồ Chí Minh</option>
                            <option value="VCCB">Ngân hàng TMCP Bản Việt</option>
                            <option value="SCB">Ngân hàng TMCP Sài Gòn</option>
                            <option value="VIB">Ngân hàng TMCP Quốc tế Việt Nam</option>
                            <option value="SHB">Ngân hàng TMCP Sài Gòn - Hà Nội</option>
                            <option value="EIB">Ngân hàng TMCP Xuất Nhập khẩu Việt Nam</option>
                            <option value="MSB">Ngân hàng TMCP Hàng Hải Việt Nam</option>
                            <option value="CAKE">TMCP Việt Nam Thịnh Vượng - Ngân hàng số CAKE by VPBank</option>
                            <option value="Ubank">TMCP Việt Nam Thịnh Vượng - Ngân hàng số Ubank by VPBank</option>
                            <option value="TIMO">Ngân hàng số Timo by Ban Viet Bank (Timo by Ban Viet Bank)</option>
                            <option value="VTLMONEY">Tổng Công ty Dịch vụ số Viettel - Chi nhánh tập đoàn công nghiệp viễn
                                thông Quân Đội</option>
                            <option value="VNPTMONEY">VNPT Money</option>
                            <option value="SGICB">Ngân hàng TMCP Sài Gòn Công Thương</option>
                            <option value="BAB">Ngân hàng TMCP Bắc Á</option>
                            <option value="PVCB">Ngân hàng TMCP Đại Chúng Việt Nam</option>
                            <option value="MBV">Ngân hàng TNHH MTV Việt Nam Hiện Đại</option>
                            <option value="NCB">Ngân hàng TMCP Quốc Dân</option>
                            <option value="SHBVN">Ngân hàng TNHH MTV Shinhan Việt Nam</option>
                            <option value="ABB">Ngân hàng TMCP An Bình</option>
                            <option value="VAB">Ngân hàng TMCP Việt Á</option>
                            <option value="NAB">Ngân hàng TMCP Nam Á</option>
                            <option value="PGB">Ngân hàng TMCP Thịnh vượng và Phát triển</option>
                            <option value="VIETBANK">Ngân hàng TMCP Việt Nam Thương Tín</option>
                            <option value="BVB">Ngân hàng TMCP Bảo Việt</option>
                            <option value="SEAB">Ngân hàng TMCP Đông Nam Á</option>
                            <option value="COOPBANK">Ngân hàng Hợp tác xã Việt Nam</option>
                            <option value="LPB">Ngân hàng TMCP Lộc Phát Việt Nam</option>
                            <option value="KLB">Ngân hàng TMCP Kiên Long</option>
                            <option value="KBank">Ngân hàng Đại chúng TNHH Kasikornbank</option>
                            <option value="KBHN">Ngân hàng Kookmin - Chi nhánh Hà Nội</option>
                            <option value="KEBHANAHCM">Ngân hàng KEB Hana – Chi nhánh Thành phố Hồ Chí Minh</option>
                            <option value="KEBHANAHN">Ngân hàng KEB Hana – Chi nhánh Hà Nội</option>
                            <option value="MAFC">Công ty Tài chính TNHH MTV Mirae Asset (Việt Nam) </option>
                            <option value="CITIBANK">Ngân hàng Citibank, N.A. - Chi nhánh Hà Nội</option>
                            <option value="KBHCM">Ngân hàng Kookmin - Chi nhánh Thành phố Hồ Chí Minh</option>
                            <option value="VBSP">Ngân hàng Chính sách Xã hội</option>
                            <option value="WVN">Ngân hàng TNHH MTV Woori Việt Nam</option>
                            <option value="VRB">Ngân hàng Liên doanh Việt - Nga</option>
                            <option value="UOB">Ngân hàng United Overseas - Chi nhánh TP. Hồ Chí Minh</option>
                            <option value="SCVN">Ngân hàng TNHH MTV Standard Chartered Bank Việt Nam</option>
                            <option value="PBVN">Ngân hàng TNHH MTV Public Việt Nam</option>
                            <option value="NHB HN">Ngân hàng Nonghyup - Chi nhánh Hà Nội</option>
                            <option value="IVB">Ngân hàng TNHH Indovina</option>
                            <option value="IBK - HCM">Ngân hàng Công nghiệp Hàn Quốc - Chi nhánh TP. Hồ Chí Minh
                            </option>
                            <option value="IBK - HN">Ngân hàng Công nghiệp Hàn Quốc - Chi nhánh Hà Nội</option>
                            <option value="HSBC">Ngân hàng TNHH MTV HSBC (Việt Nam)</option>
                            <option value="HLBVN">Ngân hàng TNHH MTV Hong Leong Việt Nam</option>
                            <option value="GPB">Ngân hàng Thương mại TNHH MTV Dầu Khí Toàn Cầu</option>
                            <option value="Vikki">Ngân hàng TNHH MTV Số Vikki</option>
                            <option value="DBS">DBS Bank Ltd - Chi nhánh Thành phố Hồ Chí Minh</option>
                            <option value="CIMB">Ngân hàng TNHH MTV CIMB Việt Nam</option>
                            <option value="CBB">Ngân hàng Thương mại TNHH MTV Xây dựng Việt Nam</option>
                        </datalist>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Thêm ngân hàng</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
