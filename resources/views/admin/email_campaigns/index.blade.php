@extends('layouts.admin') {{-- Layout Metronic --}}
@section('title', 'Email Campaigns')
@section('content')
    <div class="d-flex flex-column flex-column-fluid">

        <div id="kt_app_content" class="app-content  flex-column-fluid ">
            <div id="kt_app_content_container" class="app-container  container-xxl ">
                <div class="card card-flush">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="fa fa-search fs-4 position-absolute ms-4"></i>
                                <input type="text" class="form-control form-control-solid w-250px ps-12"
                                    placeholder="Tìm kiếm chiến dịch" />
                            </div>
                        </div>

                        <div class="card-toolbar">
                            <a href="{{ route('admin.email_campaigns.create') }}" class="btn btn-primary">Tạo chiến dịch</a>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th>Tên chiến dịch</th>
                                        <th>Tiêu đề email</th>
                                        <th>Trạng thái</th>
                                        <th>Tiến trình</th>
                                        <th>Thời gian</th>
                                        <th class="text-end">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($campaigns as $campaign)
                                        <tr>
                                            <td>{{ $campaign->campaign_name }}</td>
                                            <td>{{ $campaign->email_subject }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $campaign->status === 'completed' ? 'badge-success' : ($campaign->status === 'processing' ? 'badge-warning' : 'badge-secondary') }}">
                                                    {{ ucfirst($campaign->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ count($campaign->target_emails) }} người nhận
                                            </td>
                                            <td>{{ $campaign->created_at->format('Y-m-d H:i') }}</td>
                                            <td class="text-end">
                                                <form action="{{ route('admin.email_campaigns.destroy', $campaign->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Bạn chắc chắn muốn xóa chiến dịch này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection