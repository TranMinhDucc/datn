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
                        Chỉnh sửa ngân hàng
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
                            Chỉnh sửa</li>

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
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                CHỈNH SỬA NGÂN HÀNG
                            </div>
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

                                <!--begin::Add product-->
                                <a href="#" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-plus fs-5"><span class="path1"></span><span
                                            class="path2"></span><span class="path3"></span><span
                                            class="path4"></span></i>
                                    Thêm ngân hàng
                                </a>
                                <!--end::Add product-->
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.bank.config_update', $bank->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label for="exampleInputEmail1">Ngân hàng <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control"
                                        value="{{ old('short_name', $bank->short_name) }}" list="options" name="short_name"
                                        placeholder="Nhập tên ngân hàng" required="">
                                    <datalist id="options">
                                        <option value="THESIEURE">Ví THESIEURE.COM</option>
                                        <option value="MOMO">Ví điện tử MOMO</option>
                                        <option value="Zalo Pay">Ví điện tử Zalo Pay</option>
                                        <option value="ICB">Ngân hàng TMCP Công thương Việt Nam</option>
                                        <option value="VCB">Ngân hàng TMCP Ngoại Thương Việt Nam</option>
                                        <option value="BIDV">Ngân hàng TMCP Đầu tư và Phát triển Việt Nam</option>
                                        <option value="VBA">Ngân hàng Nông nghiệp và Phát triển Nông thôn Việt Nam
                                        </option>
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
                                        <option value="CAKE">TMCP Việt Nam Thịnh Vượng - Ngân hàng số CAKE by VPBank
                                        </option>
                                        <option value="Ubank">TMCP Việt Nam Thịnh Vượng - Ngân hàng số Ubank by VPBank
                                        </option>
                                        <option value="TIMO">Ngân hàng số Timo by Ban Viet Bank (Timo by Ban Viet Bank)
                                        </option>
                                        <option value="VTLMONEY">Tổng Công ty Dịch vụ số Viettel - Chi nhánh tập đoàn công
                                            nghiệp viễn thông Quân Đội</option>
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
                                        <option value="KEBHANAHCM">Ngân hàng KEB Hana – Chi nhánh Thành phố Hồ Chí Minh
                                        </option>
                                        <option value="KEBHANAHN">Ngân hàng KEB Hana – Chi nhánh Hà Nội</option>
                                        <option value="MAFC">Công ty Tài chính TNHH MTV Mirae Asset (Việt Nam) </option>
                                        <option value="CITIBANK">Ngân hàng Citibank, N.A. - Chi nhánh Hà Nội</option>
                                        <option value="KBHCM">Ngân hàng Kookmin - Chi nhánh Thành phố Hồ Chí Minh</option>
                                        <option value="VBSP">Ngân hàng Chính sách Xã hội</option>
                                        <option value="WVN">Ngân hàng TNHH MTV Woori Việt Nam</option>
                                        <option value="VRB">Ngân hàng Liên doanh Việt - Nga</option>
                                        <option value="UOB">Ngân hàng United Overseas - Chi nhánh TP. Hồ Chí Minh
                                        </option>
                                        <option value="SCVN">Ngân hàng TNHH MTV Standard Chartered Bank Việt Nam</option>
                                        <option value="PBVN">Ngân hàng TNHH MTV Public Việt Nam</option>
                                        <option value="NHB HN">Ngân hàng Nonghyup - Chi nhánh Hà Nội</option>
                                        <option value="IVB">Ngân hàng TNHH Indovina</option>
                                        <option value="IBK - HCM">Ngân hàng Công nghiệp Hàn Quốc - Chi nhánh TP. Hồ
                                            Chí Minh</option>
                                        <option value="IBK - HN">Ngân hàng Công nghiệp Hàn Quốc - Chi nhánh Hà Nội
                                        </option>
                                        <option value="HSBC">Ngân hàng TNHH MTV HSBC (Việt Nam)</option>
                                        <option value="HLBVN">Ngân hàng TNHH MTV Hong Leong Việt Nam</option>
                                        <option value="GPB">Ngân hàng Thương mại TNHH MTV Dầu Khí Toàn Cầu</option>
                                        <option value="Vikki">Ngân hàng TNHH MTV Số Vikki</option>
                                        <option value="DBS">DBS Bank Ltd - Chi nhánh Thành phố Hồ Chí Minh</option>
                                        <option value="CIMB">Ngân hàng TNHH MTV CIMB Việt Nam</option>
                                        <option value="CBB">Ngân hàng Thương mại TNHH MTV Xây dựng Việt Nam</option>
                                    </datalist>
                                </div>
                                <div class="row">
                                    <div class="mb-3">
                                        <label class="form-label">Hình ảnh hiện tại:</label><br>
                                        @if ($bank->image)
                                            <img src="{{ asset('storage/' . $bank->image) }}" alt="Hình hiện tại"
                                                width="100">
                                        @else
                                            <span class="text-muted">Chưa có ảnh</span>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Thay đổi ảnh mới</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>

                                </div>
                                <div class="mb-4">
                                    <label for="exampleInputEmail1">Account number</label>
                                    <input type="text" class="form-control" name="account_number"
                                        value="{{ old('account_number', $bank->account_number) }}"
                                        placeholder="Nhập số tài khoản">
                                </div>
                                <div class="mb-4">
                                    <label for="exampleInputEmail1">Account name</label>
                                    <input type="text" class="form-control" name="account_name"
                                        value="{{ old('account_name', $bank->account_name) }}"
                                        placeholder="Nhập tên chủ tài khoản">
                                </div>
                                <div class="mb-4">
                                    <label for="exampleInputEmail1">Trạng thái</label>
                                    <select class="form-control" name="status">
                                        <option selected="" value="1">ON</option>
                                        <option value="0">OFF</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="exampleInputEmail1">Password Internet Banking</label>
                                    <input type="text" class="form-control" name="password"
                                        value="{{ old('token', $bank->password) }}"
                                        placeholder="Áp dụng khi cấu hình nạp tiền tự động.">
                                </div>
                                <div class="mb-4">
                                    <label for="exampleInputEmail1">Token</label>
                                    <input type="text" class="form-control" name="token"
                                        value="{{ old('token', $bank->token) }}"
                                        placeholder="Áp dụng khi cấu hình nạp tiền tự động.">
                                </div>


                                <a type="button" class="btn btn-hero btn-danger"
                                    href="{{ route('admin.bank.config') }}"><i
                                        class="fa fa-fw fa-undo me-1"></i>
                                    Back</a>
                                <button type="submit" class="btn btn-hero btn-success"><i
                                        class="fa fa-fw fa-save me-1"></i> Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--end::Content wrapper-->
@endsection
