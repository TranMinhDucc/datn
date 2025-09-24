@extends('layouts.admin')

@section('title', 'Quản lý kho')

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
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="fas fa-search fs-3 position-absolute ms-5"></i>
                        <input type="text" name="search" id="searchInput"
                            class="form-control form-control-solid w-250px ps-13"
                            placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}" />
                    </div>
                    <!--end::Search-->

                </div>
                <!--end::Card title-->
                
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <a href="{{ route('admin.inventory.history') }}" class="btn btn-primary">
                        <i class="fas fa-history"></i> Lịch sử nhập xuất kho
                    </a>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-4 table-responsive">
                <!--begin::Table-->
                <h3>Sản phẩm có biến thể</h3>
                <div id="variant-result">
                    @include('admin.inventory._variant_table')
                </div>
                {{-- <h3>Sản phẩm không biến thể</h3>
                <div id="product-result" class="pt-10">
                    @include('admin.inventory._product_table')
                </div> --}}
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Content container-->
</div>
<!--end::Content-->
@endsection
@push('scripts')
<script>
    const debounce = (func, delay) => {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => func.apply(this, args), delay);
        };
    };

    const fetchInventoryData = debounce(function() {
        const query = $('#searchInput').val();
        $.ajax({
            url: "{{ route('admin.inventory.index') }}",
            type: 'GET',
            data: {
                search: query
            },
            beforeSend: function() {
                $('#variant-result').html('<p class="text-muted">Đang tìm kiếm...</p>');
                $('#product-result').html('');
            },
            success: function(data) {
                $('#variant-result').html(data.variants);
                $('#product-result').html(data.products);
            },
            error: function() {
                $('#variant-result').html('<p class="text-danger">Lỗi khi tìm kiếm.</p>');
            }
        });
    }, 300);

    $('#searchInput').on('keyup', fetchInventoryData);
</script>
@endpush