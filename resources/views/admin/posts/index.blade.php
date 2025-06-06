@extends('layouts.admin')

@section('content')
<div class="page-title d-flex flex-column justify-content-center flex-wrap me-1 ">
	<!--begin::Title-->
	<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
		My Projects
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
			Projects </li>
		<!--end::Item-->

	</ul>
	<!--end::Breadcrumb-->
</div>
<!--end::Page title-->
<!--begin::Actions-->
<div class="d-flex align-items-center gap-2 gap-lg-3 ">
	<!--begin::Filter menu-->
	<div class="m-0">
		<!--begin::Menu toggle-->
		<a href="#" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
			<i class="ki-duotone ki-filter fs-6 text-muted me-1"><span class="path1"></span><span class="path2"></span></i>
			Filter
		</a>
		<!--end::Menu toggle-->

		<!--begin::Menu 1-->
		<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_683933a8f3a42">
			<!--begin::Header-->
			<div class="px-7 py-5">
				<div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
			</div>
			<!--end::Header-->

			<!--begin::Menu separator-->
			<div class="separator border-gray-200"></div>
			<!--end::Menu separator-->

			<!--begin::Form-->
			<div class="px-7 py-5">
				<!--begin::Input group-->
				<div class="mb-10">
					<!--begin::Label-->
					<label class="form-label fw-semibold">Status:</label>
					<!--end::Label-->

					<!--begin::Input-->
					<div>
						<select class="form-select form-select-solid" multiple data-kt-select2="true" data-close-on-select="false" data-placeholder="Select option" data-dropdown-parent="#kt_menu_683933a8f3a42" data-allow-clear="true">
							<option></option>
							<option value="1">Approved</option>
							<option value="2">Pending</option>
							<option value="2">In Process</option>
							<option value="2">Rejected</option>
						</select>
					</div>
					<!--end::Input-->
				</div>
				<!--end::Input group-->

				<!--begin::Input group-->
				<div class="mb-10">
					<!--begin::Label-->
					<label class="form-label fw-semibold">Member Type:</label>
					<!--end::Label-->

					<!--begin::Options-->
					<div class="d-flex">
						<!--begin::Options-->
						<label class="form-check form-check-sm form-check-custom form-check-solid me-5">
							<input class="form-check-input" type="checkbox" value="1" />
							<span class="form-check-label">
								Author
							</span>
						</label>
						<!--end::Options-->

						<!--begin::Options-->
						<label class="form-check form-check-sm form-check-custom form-check-solid">
							<input class="form-check-input" type="checkbox" value="2" checked="checked" />
							<span class="form-check-label">
								Customer
							</span>
						</label>
						<!--end::Options-->
					</div>
					<!--end::Options-->
				</div>
				<!--end::Input group-->

				<!--begin::Input group-->
				<div class="mb-10">
					<!--begin::Label-->
					<label class="form-label fw-semibold">Notifications:</label>
					<!--end::Label-->

					<!--begin::Switch-->
					<div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
						<input class="form-check-input" type="checkbox" value="" name="notifications" checked />
						<label class="form-check-label">
							Enabled
						</label>
					</div>
					<!--end::Switch-->
				</div>
				<!--end::Input group-->

				<!--begin::Actions-->
				<div class="d-flex justify-content-end">
					<button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>

					<button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply</button>
				</div>
				<!--end::Actions-->
			</div>
			<!--end::Form-->
		</div>
		<!--end::Menu 1-->
	</div>
	<!--end::Filter menu-->

	<!--begin::Search-->
	<div class="position-relative me-3">
		<input type="text" class="form-control form-control-sm form-control-solid w-250px ps-9" placeholder="Tìm kiếm bài viết..." id="searchInput">
		<i class="ki-duotone ki-magnifier fs-6 position-absolute ms-4 top-50 translate-middle-y">
			<span class="path1"></span>
			<span class="path2"></span>
		</i>
	</div>
	<!--end::Search-->

	<!--begin::Primary button-->
	<a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">
		Create </a>
	<!--end::Primary button-->
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
		<!--begin::Stats-->
		<div class="row gx-6 gx-xl-9">
			<div class="col-lg-6 col-xxl-4">
				<!--begin::Card-->
				<div class="card h-100">
					<!--begin::Card body-->
					<div class="card-body p-9">
						<!--begin::Heading-->
						<div class="fs-2hx fw-bold">{{ $stats['total'] }}</div>
						<div class="fs-4 fw-semibold text-gray-500 mb-7">Tổng số bài viết</div>
						<!--end::Heading-->

						<!--begin::Wrapper-->
						<div class="d-flex flex-wrap">
							<!--begin::Chart-->
							<div class="d-flex flex-center h-100px w-100px me-9 mb-5">
								<canvas id="post_status_chart"></canvas>
							</div>
							<!--end::Chart-->

							<!--begin::Labels-->
							<div class="d-flex flex-column justify-content-center flex-row-fluid pe-11 mb-5">
								<!--begin::Label-->
								<div class="d-flex fs-6 fw-semibold align-items-center mb-3">
									<div class="bullet bg-success me-3"></div>
									<div class="text-gray-500">Đã đăng</div>
									<div class="ms-auto fw-bold text-gray-700">{{ $stats['published'] }}</div>
								</div>
								<!--end::Label-->

								<div class="d-flex fs-6 fw-semibold align-items-center mb-3">
									<div class="bullet bg-secondary me-3"></div>
									<div class="text-gray-500">Nháp</div>
									<div class="ms-auto fw-bold text-gray-700">{{ $stats['draft'] }}</div>
								</div>

								<div class="d-flex fs-6 fw-semibold align-items-center">
									<div class="bullet bg-danger me-3"></div>
									<div class="text-gray-500">Bị ẩn</div>
									<div class="ms-auto fw-bold text-gray-700">{{ $stats['hidden'] }}</div>
								</div>
							</div>
							<!--end::Labels-->
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Card body-->
				</div>
				<!--end::Card-->
			</div>

			<div class="col-lg-6 col-xxl-4">
				<!--begin::Views Stats-->
				<div class="card h-100">
					<div class="card-body p-9">
						<div class="fs-2hx fw-bold">{{ number_format($stats['total_views']) }}</div>
						<div class="fs-4 fw-semibold text-gray-500 mb-7">Tổng lượt xem</div>

						<div class="fs-6 d-flex justify-content-between mb-4">
							<div class="fw-semibold">Trung bình mỗi bài</div>
							<div class="d-flex fw-bold">
								<i class="ki-duotone ki-arrow-up-right fs-3 me-1 text-success">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								{{ $stats['total'] > 0 ? number_format($stats['total_views'] / $stats['total']) : 0 }}
							</div>
						</div>

						<div class="separator separator-dashed"></div>

						<div class="fs-6 d-flex justify-content-between my-4">
							<div class="fw-semibold">Bài xem nhiều nhất</div>
							<div class="d-flex fw-bold">
								<i class="ki-duotone ki-eye fs-3 me-1 text-primary">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
								</i>
								{{ number_format($stats['max_views']) }}
							</div>
						</div>

						<div class="separator separator-dashed"></div>

						<div class="fs-6 d-flex justify-content-between mt-4">
							<div class="fw-semibold">Hôm nay</div>
							<div class="d-flex fw-bold">
								<i class="ki-duotone ki-calendar fs-3 me-1 text-info">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								{{ number_format($stats['today_views']) }}
							</div>
						</div>
					</div>
				</div>
				<!--end::Views Stats-->
			</div>

			<!-- Phần trống thứ 3 có thể thêm thống kê khác -->
			<div class="col-lg-6 col-xxl-4">
				<div class="card h-100">
					<div class="card-body p-9">
						<!--begin::Heading-->
						<div class="fs-4 fw-semibold text-gray-700 mb-7">
							<i class="ki-duotone ki-rocket fs-3 me-2 text-primary">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							Thao tác nhanh
						</div>
						<!--end::Heading-->

						<!--begin::Quick buttons-->
						<div class="d-grid gap-3 mb-7">
							<a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-flex">
								<i class="ki-duotone ki-plus fs-2 me-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								Viết bài mới
							</a>

							@if($stats['draft'] > 0)
							<a href="{{ route('admin.posts.index', ['status' => 0]) }}" class="btn btn-warning btn-flex">
								<i class="ki-duotone ki-notepad-edit fs-2 me-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								{{ $stats['draft'] }} bài nháp cần hoàn thành
							</a>
							@endif

							@if($stats['hidden'] > 0)
							<a href="{{ route('admin.posts.index', ['status' => 2]) }}" class="btn btn-danger btn-flex">
								<i class="ki-duotone ki-eye-slash fs-2 me-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
									<span class="path4"></span>
								</i>
								{{ $stats['hidden'] }} bài bị ẩn
							</a>
							@endif
						</div>
						<!--end::Quick buttons-->

						<!--begin::Divider-->
						<div class="separator separator-dashed mb-5"></div>
						<!--end::Divider-->

						<!--begin::Recent activity-->
						<div class="fs-6 fw-semibold text-gray-500 mb-4">
							<i class="ki-duotone ki-time fs-4 me-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							Hoạt động gần đây
						</div>

						<div class="d-flex flex-column gap-3">
							<!--begin::Activity item-->
							<div class="d-flex align-items-center">
								<div class="bullet bullet-dot bg-success me-3"></div>
								<div class="fs-7 text-muted flex-grow-1">
									Hôm nay có <span class="fw-bold text-gray-800">{{ $stats['today_views'] }}</span> lượt xem
								</div>
							</div>
							<!--end::Activity item-->

							<!--begin::Activity item-->
							<div class="d-flex align-items-center">
								<div class="bullet bullet-dot bg-primary me-3"></div>
								<div class="fs-7 text-muted flex-grow-1">
									Trung bình <span class="fw-bold text-gray-800">{{ $stats['total'] > 0 ? number_format($stats['total_views'] / $stats['total'], 0) : 0 }}</span> view/bài
								</div>
							</div>
							<!--end::Activity item-->

							<!--begin::Activity item-->
							<div class="d-flex align-items-center">
								<div class="bullet bullet-dot bg-info me-3"></div>
								<div class="fs-7 text-muted flex-grow-1">
									Tỷ lệ xuất bản: <span class="fw-bold text-gray-800">{{ $stats['total'] > 0 ? round(($stats['published'] / $stats['total']) * 100, 1) : 0 }}%</span>
								</div>
							</div>
							<!--end::Activity item-->
						</div>
						<!--end::Recent activity-->

						
					</div>
				</div>
			</div>
		</div>
		<!--end::Stats-->

		<!--begin::Toolbar-->
		<div class="d-flex flex-wrap flex-stack my-5">
			<!--begin::Heading-->
			<h2 class="fs-2 fw-semibold my-2">
				Projects
				<span class="fs-6 text-gray-500 ms-1">by Status</span>
			</h2>
			<!--end::Heading-->

			<!--begin::Controls-->
			<div class="d-flex flex-wrap my-1">
				<!--begin::Select wrapper-->
				<div class="m-0">
					<!--begin::Select-->
					<select name="status" data-control="select2" data-hide-search="true" class="form-select form-select-sm form-select-solid fw-bold w-125px">
						<option value="Active" selected>Active</option>
						<option value="Approved">In Progress</option>
						<option value="Declined">To Do</option>
						<option value="In Progress">Completed</option>
					</select>
					<!--end::Select-->
				</div>
				<!--end::Select wrapper-->
			</div>
			<!--end::Controls-->
		</div>
		<!--end::Toolbar-->

		<!--begin::Main Content Row-->
		<div class="row g-6 g-xl-9">
			<!--begin::Posts Grid-->
			<div class="col-xxl-9 col-xl-8">
				<!--begin::Posts Row-->
				<div class="row g-6 g-xl-9">
					@foreach($posts as $post)
					<!--begin::Col-->
					<div class="col-md-6 col-xl-6 d-flex">
						<!--begin::Card-->
						<div class="card border-hover-primary h-100 w-100 d-flex flex-column position-relative card-hover-actions">
							<!--begin::Card header-->
							<div class="card-header border-0 pt-9 flex-shrink-0">
								<!--begin::Card Title-->
								<div class="card-title m-0">
									<div class="symbol symbol-50px w-50px bg-light">
										<img src="{{ asset('storage/' . $post->thumbnail) }}" alt="image" class="p-1" />
									</div>
								</div>
								<!--end::Card Title-->

								<!--begin::Card toolbar-->
								<div class="card-toolbar">
									@php
									$badge = match ($post->status) {
									0 => 'badge-light-secondary',
									1 => 'badge-light-success',
									2 => 'badge-light-danger',
									default => 'badge-light-info'
									};
									$statusText = match ($post->status) {
									0 => 'Nháp',
									1 => 'Đã đăng',
									2 => 'Bị ẩn',
									default => 'Khác'
									};
									@endphp
									<span class="badge {{ $badge }} fw-bold px-4 py-3">{{ $statusText }}</span>
								</div>
								<!--end::Card toolbar-->
							</div>
							<!--end:: Card header-->

							<!--begin:: Card body-->
							<div class="card-body p-9 d-flex flex-column flex-grow-1">
								<!--begin::Name-->
								<div class="fs-3 fw-bold text-gray-900 mb-3">
									{{ Str::limit($post->title, 45) }}
								</div>
								<!--end::Name-->

								<!--begin::Description-->
								<p class="text-gray-500 fw-semibold fs-5 mb-7 flex-grow-1">
									{{ Str::limit(strip_tags($post->content), 80) }}
								</p>
								<!--end::Description-->

								<!--begin::Info-->
								<div class="d-flex flex-wrap mt-auto">
									<!--begin::Created At-->
									<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-3 mb-3">
										<div class="fs-6 text-gray-800 fw-bold">{{ $post->created_at->format('M d, Y') }}</div>
										<div class="fw-semibold text-gray-500">Ngày tạo</div>
									</div>
									<!--end::Created At-->

									<!--begin::View Count-->
									<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 mb-3">
										<div class="fs-6 text-gray-800 fw-bold">{{ $post->view }}</div>
										<div class="fw-semibold text-gray-500">Lượt xem</div>
									</div>
									<!--end::View Count-->
								</div>
								<!--end::Info-->
							</div>
							<!--end:: Card body-->

							<!--begin::Hover Actions Overlay-->
							<div class="card-hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(0,0,0,0.7); opacity: 0; transition: all 0.3s ease; z-index: 10;">
								<div class="d-flex gap-3 align-items-center">
									<!--begin::View Button-->
									<a href="{{ route('admin.posts.show', $post) }}" class="btn btn-primary btn-sm px-4 py-2 fw-bold d-flex align-items-center" style="height: 38px;">
										<i class="ki-duotone ki-eye fs-4 me-2">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
										</i>
										Xem
									</a>
									<!--end::View Button-->

									<!--begin::Edit Button-->
									<a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning btn-sm px-4 py-2 fw-bold d-flex align-items-center" style="height: 38px;">
										<i class="ki-duotone ki-pencil fs-4 me-2">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
										Sửa
									</a>
									<!--end::Edit Button-->

									<!--begin::Delete Button-->
									<form method="POST" action="{{ route('admin.posts.destroy', $post) }}" style="display: inline; margin: 0;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết \'{{ addslashes($post->title) }}\' không?')">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-danger btn-sm px-4 py-2 fw-bold d-flex align-items-center" style="height: 38px;">
											<i class="ki-duotone ki-trash fs-4 me-2">
												<span class="path1"></span>
												<span class="path2"></span>
												<span class="path3"></span>
												<span class="path4"></span>
												<span class="path5"></span>
											</i>
											Xóa
										</button>
									</form>
									<!--end::Delete Button-->
								</div>
							</div>
							<!--end::Hover Actions Overlay-->
						</div>
						<!--end::Card-->
					</div>
					<!--end::Col-->
					@endforeach
				</div>
				<!--end::Posts Row-->

				<!-- Phân trang -->
				<div class="mt-5">
					{{ $posts->links() }}
				</div>
			</div>
			<!--end::Posts Grid-->

			<!--begin::Sidebar-->
			<div class="col-xxl-3 col-xl-4">
				<!--begin::Popular Posts Sidebar-->
				<div class="card h-100 sticky-top" style="top: 20px;">
					<div class="card-body p-6">
						<!--begin::Heading-->
						<div class="d-flex align-items-center mb-6">
							<i class="ki-duotone ki-chart-line-up fs-2 text-primary me-3">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							<div>
								<div class="fs-3 fw-bold text-gray-900">Top 5</div>
								<div class="fs-6 fw-semibold text-gray-500">Bài viết phổ biến</div>
							</div>
						</div>
						<!--end::Heading-->

						<!--begin::Posts list-->
						<div class="mb-0">
							@foreach($stats['popular_posts'] as $index => $post)
							<div class="d-flex align-items-center mb-5 {{ !$loop->last ? 'pb-4 border-bottom border-gray-200' : '' }}">
								<!--begin::Rank-->
								<div class="symbol symbol-40px symbol-circle me-4">
									<span class="symbol-label bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'info' : ($index == 2 ? 'success' : 'light')) }} text-{{ $index <= 2 ? 'inverse-' . ($index == 0 ? 'warning' : ($index == 1 ? 'info' : 'success')) : 'gray-800' }} fw-bold fs-6">
										{{ $index + 1 }}
									</span>
								</div>
								<!--end::Rank-->

								<!--begin::Post info-->
								<div class="flex-grow-1 min-w-0">
									<a href="{{ route('admin.posts.show', $post) }}" class="text-gray-800 text-hover-primary fw-bold fs-6 mb-1 d-block" style="line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $post->title }}">
										{{ Str::limit($post->title, 50) }}
									</a>
									<div class="d-flex align-items-center">
										<span class="text-muted fw-semibold fs-7 me-3">
											<i class="ki-duotone ki-eye fs-7 me-1">
												<span class="path1"></span>
												<span class="path2"></span>
												<span class="path3"></span>
											</i>
											{{ number_format($post->view) }}
										</span>
										<span class="text-muted fw-semibold fs-7">
											<i class="ki-duotone ki-calendar fs-7 me-1">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
											{{ $post->created_at->format('M d') }}
										</span>
									</div>
								</div>
								<!--end::Post info-->
							</div>
							@endforeach
						</div>
						<!--end::Posts list-->

						<!--begin::View All Button-->
						<div class="text-center mt-6">
							<a href="#" class="btn btn-light-primary fw-bold w-100">
								<i class="ki-duotone ki-arrow-right fs-3 ms-2">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								Xem tất cả bài viết
							</a>
						</div>
						<!--end::View All Button-->
					</div>
				</div>
				<!--end::Popular Posts Sidebar-->
			</div>
			<!--end::Sidebar-->
		</div>
		<!--end::Main Content Row-->
	</div>
	<!--end::Content container-->
</div>
<!--end::Content-->

<!--begin::Javascript-->
<script>
	var hostUrl = "../../assets/index.html";
</script>

<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="../../assets/plugins/global/plugins.bundle.js"></script>
<script src="../../assets/js/scripts.bundle.js"></script>
<!--end::Global Javascript Bundle-->

<!--begin::Vendors Javascript(used for this page only)-->
<script src="../../assets/plugins/custom/datatables/datatables.bundle.js"></script>
<!--end::Vendors Javascript-->

<!--begin::Custom Javascript(used for this page only)-->
<script src="../../assets/js/custom/apps/projects/list/list.js"></script>
<script src="../../assets/js/widgets.bundle.js"></script>
<script src="../../assets/js/custom/widgets.js"></script>
<script src="../../assets/js/custom/apps/chat/chat.js"></script>
<script src="../../assets/js/custom/utilities/modals/upgrade-plan.js"></script>
<script src="../../assets/js/custom/utilities/modals/create-app.js"></script>
<script src="../../assets/js/custom/utilities/modals/users-search.js"></script>
<!--end::Custom Javascript-->

<script>
	// Search functionality
	document.getElementById('searchInput').addEventListener('input', function(e) {
		const searchTerm = e.target.value.toLowerCase().trim();
		const postCards = document.querySelectorAll('.card-hover-actions');

		postCards.forEach(card => {
			const title = card.querySelector('.fs-3').textContent.toLowerCase();
			const content = card.querySelector('.text-gray-500').textContent.toLowerCase();

			if (title.includes(searchTerm) || content.includes(searchTerm)) {
				card.closest('.col-md-6').style.display = 'block';
			} else {
				card.closest('.col-md-6').style.display = 'none';
			}
		});
	});
</script>

@endsection

<style>
	.card-hover-actions:hover .card-hover-overlay {
		opacity: 1 !important;
	}

	.card-hover-actions {
		cursor: pointer;
		overflow: hidden;
	}

	.card-hover-actions:hover {
		transform: translateY(-2px);
		box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
		transition: all 0.3s ease;
	}

	/* Responsive adjustments */
	@media (max-width: 1399px) {
		.sticky-top {
			position: relative !important;
			top: auto !important;
		}
	}

	/* Custom scrollbar for sidebar */
	.card-body::-webkit-scrollbar {
		width: 4px;
	}

	.card-body::-webkit-scrollbar-track {
		background: #f1f1f1;
		border-radius: 10px;
	}

	.card-body::-webkit-scrollbar-thumb {
		background: #c1c1c1;
		border-radius: 10px;
	}

	.card-body::-webkit-scrollbar-thumb:hover {
		background: #a8a8a8;
	}
</style>