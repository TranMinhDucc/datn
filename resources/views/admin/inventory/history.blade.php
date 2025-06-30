@extends('layouts.admin')
@section('title', 'Lịch sử nhập xuất kho')

@section('content')
<div class="app-content flex-column-fluid">
    <div class="app-container container-xxl">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <input type="text" name="search" id="historySearch"
                        class="form-control w-250px" placeholder="Tìm sản phẩm..."
                        value="{{ request('search') }}">
                    <form method="GET" class="d-flex gap-3">
                        <select name="type" class="form-select w-150px">
                            <option value="">-- Tất cả --</option>
                            <option value="import" {{ request('type') == 'import' ? 'selected' : '' }}>Nhập kho</option>
                            <option value="export" {{ request('type') == 'export' ? 'selected' : '' }}>Xuất kho</option>
                            <option value="adjust" {{ request('type') == 'adjust' ? 'selected' : '' }}>Điều chỉnh</option>
                            <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Hoàn hàng</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Lọc</button>
                    </form>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại kho
                    </a>
                </div>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-row-dashed fs-6 gy-4">
                    <thead>
                        <tr class="text-gray-500 fw-bold fs-7 text-uppercase">
                            <th>Sản phẩm</th>
                            <th>Biến thể</th>
                            <th>Loại</th>
                            <th>Số lượng</th>
                            <th>Ghi chú</th>
                            <th>Người thao tác</th>
                            <th>Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $tran)
                        <tr>
                            <td>
                                {{ $tran->product->name ?? '-' }}
                            </td>
                            <td>
                                @if ($tran->productVariant)
                                @foreach ($tran->productVariant->options as $opt)
                                <div>{{ $opt->attribute->name }}: {{ $opt->value->value }}</div>
                                @endforeach
                                @else
                                Không có biến thể
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ [
                                    'import' => 'success',
                                    'export' => 'danger',
                                    'adjust' => 'warning',
                                    'return' => 'info'
                                ][$tran->type] ?? 'secondary' }}">
                                    {{ ucfirst($tran->type) }}
                                </span>
                            </td>
                            <td>{{ $tran->quantity }}</td>
                            <td>{{ $tran->note ?? '-' }}</td>
                            <td>{{ $tran->user->username ?? 'System' }}</td>
                            <td>{{ $tran->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Không có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="pt-5">
                    {{ $transactions->appends(request()->query())->links('vendor.pagination.adminPagi') }}
                </div>
            </div>
        </div>
    </div>
</div>
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

    const fetchHistoryData = debounce(function() {
        const query = $('#historySearch').val();
        const type = $('[name="type"]').val();

        $.ajax({
            url: "{{ route('admin.inventory.history') }}",
            type: 'GET',
            data: {
                search: query,
                type
            },
            beforeSend: function() {
                $('.card-body').html('<p class="text-muted">Đang tìm kiếm...</p>');
            },
            success: function(data) {
                $('.card-body').html(data);
            },
            error: function() {
                $('.card-body').html('<p class="text-danger">Lỗi khi tìm kiếm</p>');
            }
        });
    }, 300);

    $('#historySearch, [name="type"]').on('input change', fetchHistoryData);
</script>
@endpush