@extends('layouts.admin')

@section('title', 'Danh sách liên hệ')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Danh sách liên hệ
                    </h1>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="fas fa-search fs-4 position-absolute ms-4"></i>
                                <input type="text" class="form-control form-control-solid w-250px ps-12"
                                    placeholder="Tìm kiếm liên hệ..." />
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th>STT</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Tiêu đề</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày gửi</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach($contacts as $c)
                                    <tr>
                                        <td>{{ $c->id }}</td>
                                        <td>{{ $c->name }}</td>
                                        <td>{{ $c->email }}</td>
                                        <td>{{ $c->subject }}</td>
<td>
    <span class="badge {{ $c->statusreply ? 'bg-success' : 'bg-warning text-dark' }}">
        {{ $c->statusreply ? 'Đã phản hồi' : 'Chưa phản hồi' }}
    </span>
</td>


                                        <td>{{ $c->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.contacts.show', $c->id) }}" class="btn btn-sm btn-light-primary">Xem</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-5">
                            {{ $contacts->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>
@endsection
