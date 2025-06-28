@extends('layouts.admin')

@section('title', 'Danh sách Blog')

@section('content')


	<!--begin::Content-->
	<div id="kt_app_content" class="app-content flex-column-fluid">
		<!--begin::Content container-->
		<div id="kt_app_content_container" class="app-container container-xxl">
			<!--begin::Card-->
			<div class="card">
				<!--begin::Card header-->
				<div class="card-header border-0 pt-6">
					<!--begin::Card title-->
					<div class="card-title">
						<form method="GET" action="{{ route('admin.search') }}">
							<input type="hidden" name="module" value="blogs">
							<div class="d-flex align-items-center position-relative my-1">
								<i class="fa-solid fa-magnifying-glass fs-4 position-absolute ms-4"></i>
								<input type="text" name="keyword" value="{{ request('keyword') }}"
									class="form-control form-control-solid w-250px ps-12" placeholder="Tìm kiếm bài viết" />
							</div>
						</form>
					</div>


					<!--begin::Card title-->
					<!--begin::Card toolbar-->
					<div class="card-toolbar">
						<!--begin::Toolbar-->
						<div class="d-flex justify-content-end" data-kt-blog-table-toolbar="base">
							<!--begin::Add blog-->
							<a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
								<i class="fas fa-plus fs-2"></i></i>Thêm Blog
							</a>
							<!--end::Add blog-->
						</div>
						<!--end::Toolbar-->
					</div>
					<!--end::Card toolbar-->
				</div>

				<!--end::Card toolbar-->
			</div>
			<!--end::Card header-->
			<!--begin::Card body-->
			<div class="card-body py-4 table-responsive">
				<!--begin::Table-->
				<table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_blogs_table">
					<thead>
						<tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
							<th class="w-10px pe-2">
								<div class="form-check form-check-sm form-check-custom form-check-solid me-3">
									<input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_blogs_table .form-check-input" value="1" />
								</div>
							</th>
							<th class="min-w-30px">ID</th>
							<th class="min-w-200px">Tiêu đề</th>
							<th class="min-w-100px">Slug</th>
							<th class="min-w-150px">Ảnh đại diện</th>
							<th class="min-w-100px">Danh mục</th>
							<th class="min-w-150px">Tác giả</th>
							<th class="min-w-100px">Trạng thái</th>
							<th class="min-w-100px">Ngày tạo</th>
							<th class="text-end min-w-100px">Thao tác</th>
						</tr>
					</thead>
					<tbody class="text-gray-600 fw-semibold">
						@forelse($blogs as $blog)
						<tr>
							<td>
								<div class="form-check form-check-sm form-check-custom form-check-solid">
									<input class="form-check-input" type="checkbox" value="{{ $blog->id }}" />
								</div>
							</td>
							<td>
								<span class="text-gray-800 text-hover-primary mb-1">#{{ $blog->id }}</span>
							</td>
							<td style="max-width: 250px;">
								<div class="d-flex flex-column">
									<a href="{{ route('admin.blogs.show', $blog->id) }}" class="text-gray-800 text-hover-primary mb-1">
										{{ $blog->title }}
									</a>
									<span class="text-muted">{{ Str::limit(strip_tags($blog->content), 60) }}</span>
								</div>
							</td>
							<td style="max-width: 100px;">
								<span class="text-muted">{{ $blog->slug }}</span>
							</td>
							<td>
								@if($blog->thumbnail)
								<img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="Thumbnail" class="w-80px h-45px rounded" />
								@else
								<span class="text-muted">Chưa có ảnh</span>
								@endif
							</td>
							<td>
								@if($blog->category)
								<span class="badge badge-light-primary">{{ $blog->category->name }}</span>
								@else
								<span class="badge badge-light-secondary">Chưa phân loại</span>
								@endif
							</td>
							<td>
								<div class="d-flex align-items-center">
									<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
										<div class="symbol-label">
											<div class="symbol-label fs-6 bg-light-info text-info">
												{{ strtoupper(substr($blog->author->username ?? 'N', 0, 1)) }}
											</div>
										</div>
									</div>
									<div class="d-flex flex-column">
										<span class="text-gray-800 fw-bold">{{ $blog->author->username ?? 'N/A' }}</span>
										<span class="text-muted fs-7">{{ $blog->author->email ?? '' }}</span>
									</div>
								</div>
							</td>
							<td>
								@if($blog->status === 'published')
								<span class="badge badge-light-success">Đang hiển thị</span>
								@elseif($blog->status === 'draft')
								<span class="badge badge-light-danger">Chưa xuất bản</span>
								@endif
							</td>
							<td>
								<span class="text-muted">{{ $blog->created_at->format('d/m/Y H:i') }}</span>
							</td>
							<td class="text-end">
								<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
									Thao tác
									<i class="fa-solid fa-chevron-down fs-5 ms-1"></i>
								</a>
								<!--begin::Menu-->
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3 text-warning"
											data-kt-blog-table-filter="toggle_status"
											data-url="{{ route('admin.blogs.toggle-status', $blog->slug) }}"
											data-status="{{ $blog->status }}">
											{{ $blog->status === 'published' ? 'Ẩn bài viết' : 'Xuất bản' }}
										</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="{{ route('admin.blogs.show', $blog->slug) }}" class="menu-link px-3">
											Xem
										</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="{{ route('admin.blogs.edit', $blog->slug) }}" class="menu-link px-3">
											Sửa
										</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3" data-kt-blog-table-filter="delete_row" data-url="{{ route('admin.blogs.destroy', $blog->slug) }}">
											Xóa
										</a>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::Menu-->
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="9" class="text-center py-10">
								<div class="text-gray-400">
									<i class="fa-solid fa-file-circle-xmark fs-3x mb-3"></i>
									<div class="fw-semibold">Chưa có blog nào</div>
								</div>
						</tr>
						@endforelse
					</tbody>
				</table>
				<!--end::Table-->
				<!-- Phần pagination được sửa -->
				@if($blogs->hasPages())
				<div class="d-flex flex-stack flex-wrap pt-10">
					<div class="fs-6 fw-semibold text-gray-700">
						Hiển thị {{ $blogs->firstItem() ?? 0 }} đến {{ $blogs->lastItem() ?? 0 }}
						trong tổng số {{ $blogs->total() }} kết quả
					</div>
					<div class="d-flex align-items-center">
						{{-- Giữ lại tham số search khi phân trang --}}
						{{ $blogs->appends(request()->query())->links('vendor.pagination.adminPagi') }}
					</div>
				</div>
				@endif
			</div>
			<!--end::Card body-->
		</div>
		<!--end::Card-->
	</div>
	<!--end::Content-->
@endsection

@push('scripts')
<script>
	//Thay đổi trạng thái blog
	document.querySelectorAll('[data-kt-blog-table-filter="toggle_status"]').forEach(el => {
		el.addEventListener('click', function(e) {
			e.preventDefault();

			const url = this.dataset.url;
			const currentStatus = this.dataset.status;
			const actionText = currentStatus === 'published' ? 'Ẩn bài viết' : 'Xuất bản';
			const confirmText = currentStatus === 'published' ?
				'Bạn có chắc muốn ẩn bài viết này không?' :
				'Bạn có chắc muốn xuất bản bài viết này không?';

			Swal.fire({
				title: 'Xác nhận thay đổi trạng thái',
				text: confirmText,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Xác nhận',
				cancelButtonText: 'Hủy',
				reverseButtons: true
			}).then(result => {
				if (result.isConfirmed) {
					// Tạo form để submit
					const form = document.createElement('form');
					form.method = 'POST';
					form.action = url;
					form.style.display = 'none';

					// CSRF token
					const csrfInput = document.createElement('input');
					csrfInput.type = 'hidden';
					csrfInput.name = '_token';
					csrfInput.value = '{{ csrf_token() }}';
					form.appendChild(csrfInput);

					// Gắn form và submit
					document.body.appendChild(form);
					form.submit();
				}
			});
		});
	});
	//Xoá blog
	document.querySelectorAll('[data-kt-blog-table-filter="delete_row"]').forEach(el => {
		el.addEventListener('click', function(e) {
			e.preventDefault();

			const url = this.dataset.url;

			Swal.fire({
				title: 'Xác nhận xoá',
				text: 'Bạn có chắc chắn muốn xoá blog này? Hành động này không thể hoàn tác!',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Xóa',
				cancelButtonText: 'Hủy',
				confirmButtonColor: '#d33',
				reverseButtons: true
			}).then(result => {
				if (result.isConfirmed) {
					const form = document.createElement('form');
					form.method = 'POST';
					form.action = url;
					form.style.display = 'none';

					// CSRF token
					const csrfInput = document.createElement('input');
					csrfInput.type = 'hidden';
					csrfInput.name = '_token';
					csrfInput.value = '{{ csrf_token() }}';
					form.appendChild(csrfInput);

					// Method spoofing DELETE
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
	//searchInput
	let searchInput = document.getElementById('searchInput');
	let timer;

	document.addEventListener('DOMContentLoaded', function() {
		console.log("Script đã chạy");

		const searchInput = document.getElementById('searchInput');
		let timer;

		if (searchInput) {
			searchInput.addEventListener('input', function() {
				clearTimeout(timer);
				timer = setTimeout(function() {
					let search = searchInput.value.trim();
					let params = new URLSearchParams(window.location.search);

					if (search.length) {
						params.set('search', search);
					} else {
						params.delete('search');
					}

					window.location.href = `${window.location.pathname}?${params.toString()}`;
				}, 500);
			});
		} else {
			console.warn("Không tìm thấy phần tử #searchInput");
		}
	});
</script>
@endpush