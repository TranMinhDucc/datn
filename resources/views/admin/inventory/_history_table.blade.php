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
            <td>{{ $tran->user->name ?? 'System' }}</td>
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