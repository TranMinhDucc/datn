@extends('layouts.admin')

@section('title', 'FAQ')

@section('content')
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div style="margin:20px 0px 20px 0px">
                <h1>Quản lí FAQ</h1>
            </div>
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="fas fa-search fs-3 position-absolute ms-5"></i>
                            <input type="text" data-kt-user-table-filter="search"
                                class="form-control form-control-solid w-250px ps-13" placeholder="Tìm kiếm câu hỏi..." />
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <a href="{{ route('admin.faq.create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i>
                                Thêm câu hỏi mới
                            </a>
                        </div>
                    </div>
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <!--begin::Table head-->
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#kt_table_users .form-check-input" value="1" />
                                        </div>
                                    </th>
                                    <th class="min-w-125px">STT</th>
                                    <th class="min-w-300px">Câu hỏi</th>
                                    <th class="min-w-400px">Trả lời</th>
                                    <th class="min-w-125px">Ngày tạo</th>
                                    <th class="text-end min-w-100px">Hành động</th>
                                </tr>
                            </thead>
                            <!--end::Table head-->

                            <!--begin::Table body-->
                            <tbody class="text-gray-600 fw-semibold">
                                @foreach ($faqs as $faq)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" value="{{ $faq->id }}" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="badge badge-light-primary fw-bold">{{ $loop->iteration }}</div>
                                        </td>
                                        <td class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <div class="symbol-label">
                                                    <i class="fas fa-clipboard-list fs-2x text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span
                                                    class="text-gray-800 text-hover-primary mb-1 fw-bold">{{ Str::limit($faq->question, 50) }}</span>
                                                <span class="text-muted fs-7">{{ Str::limit($faq->question, 100) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-gray-800">
                                                {{ Str::limit($faq->answer, 80) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="badge badge-light fw-bold">{{ $faq->created_at->format('d/m/Y') }}</div>
                                            <div class="text-muted fs-7">{{ $faq->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="text-end">
                                            <a href="#"
                                                class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                Hành động
                                                <i class="fas fa-chevron-down fs-5 ms-1"></i>
                                            </a>
                                            <!--begin::Menu-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                data-kt-menu="true">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('admin.faq.edit', $faq->id) }}" class="menu-link px-3">
                                                        <i class="fas fa-pencil-alt fs-6 me-2"></i>
                                                        Chỉnh sửa
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" data-kt-users-table-filter="delete_row"
                                                        data-faq-id="{{ $faq->id }}">
                                                        <i class="fas fa-trash fs-6 me-2"></i>
                                                        Xóa
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu-->
                                        </td>
                                    </tr>
                                @endforeach

                                @if($faqs->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center py-10">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-clipboard-list fs-4x text-muted mb-4"></i>
                                                <div class="text-gray-500 fs-6 fw-semibold">Chưa có câu hỏi thường gặp nào</div>
                                                <div class="text-muted fs-7">Nhấn vào nút "Thêm câu hỏi mới" để bắt đầu</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <div class="d-flex justify-content-end mt-4">
                            {{ $faqs->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->

            <!-- Delete Form Template (Hidden) -->
            <form id="delete-form-template" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handle delete action
            document.querySelectorAll('[data-kt-users-table-filter="delete_row"]').forEach(function (element) {
                element.addEventListener('click', function (e) {
                    e.preventDefault();

                    const faqId = this.getAttribute('data-faq-id');

                    Swal.fire({
                        text: "Bạn có chắc chắn muốn xóa câu hỏi này?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Có, xóa đi!",
                        cancelButtonText: "Hủy bỏ",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                    }).then(function (result) {
                        if (result.value) {
                            // Create and submit delete form
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '/admin/faq/' + faqId;

                            // Add CSRF token
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';
                            form.appendChild(csrfInput);

                            // Add method override
                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            form.appendChild(methodInput);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });

            // Handle search functionality
            const searchInput = document.querySelector('[data-kt-user-table-filter="search"]');
            if (searchInput) {
                searchInput.addEventListener('keyup', function (e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const tableRows = document.querySelectorAll('#kt_table_users tbody tr');

                    tableRows.forEach(function (row) {
                        if (row.cells.length > 1) {
                            const questionText = row.cells[2].textContent.toLowerCase();
                            const answerText = row.cells[3].textContent.toLowerCase();

                            if (questionText.includes(searchTerm) || answerText.includes(searchTerm)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                });
            }

            // Handle select all checkbox
            const selectAllCheckbox = document.querySelector('[data-kt-check="true"]');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function () {
                    const checkboxes = document.querySelectorAll('#kt_table_users .form-check-input[type="checkbox"]:not([data-kt-check="true"])');
                    checkboxes.forEach(function (checkbox) {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                });
            }
        });
    </script>
@endpush