<table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_inventory_table">
    <thead>
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th style="min-width: 180px;">Sản phẩm</th>
                <th style="min-width: 160px;">Biến thể</th>
                <th style="min-width: 100px;">SKU</th>
                <th style="min-width: 80px;">Tồn kho</th>
                <th style="min-width: 80px;">Đang giữ</th>
                <th style="min-width: 200px;">Thao tác</th>
            </tr>
        </thead>
    <tbody>
        @foreach ($variants as $variant)
        <tr>
            <td>{{ $variant->product?->name ?? '—' }}</td>
            <td>
                @foreach ($variant->options as $opt)
                {{ $opt->attribute->name }}: {{ $opt->value->value ?? '' }}<br>
                @endforeach
            </td>
            <td>{{ $variant->sku }}</td>
            <td>{{ $variant->quantity }}</td>
            <td>{{ $variant->reserved_quantity?? 0 }}</td>
            <td>
                @include('admin.inventory._form', ['id' => $variant->id, 'type' => 'variant'])
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<!--end::Table-->
<!-- Phần pagination cho sp có biến thể -->
@if($variants->hasPages())
<div class="d-flex flex-stack flex-wrap pt-10">
    <div class="fs-6 fw-semibold text-gray-700">
        Hiển thị {{ $variants->firstItem() ?? 0 }} đến {{ $variants->lastItem() ?? 0 }}
        trong tổng số {{ $variants->total() }} kết quả
    </div>
    <div class="d-flex align-items-center">
        {{-- Giữ lại tham số search khi phân trang --}}
        {{ $variants->appends(request()->query())->links('vendor.pagination.adminPagi') }}
    </div>
</div>
@endif