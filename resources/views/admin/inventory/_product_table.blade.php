<!--begin::Table-->
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
                        @foreach ($productsWithoutVariants as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>Không có biến thể</td>
                            <td>{{ $product->sku ?? 'N/A' }}</td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td>{{ $product->reserved_quantity ?? 0 }}</td>
                            <td>
                                @include('admin.inventory._form', ['id' => $product->id, 'type' => 'product'])
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!--end::Table-->
                <!-- Phần pagination cho sản phẩm không biến thể -->
                @if($productsWithoutVariants->hasPages())
                <div class="d-flex flex-stack flex-wrap pt-10">
                    <div class="fs-6 fw-semibold text-gray-700">
                        Hiển thị {{ $productsWithoutVariants->firstItem() }} đến {{ $productsWithoutVariants->lastItem() }}
                        trong tổng số {{ $productsWithoutVariants->total() }} sản phẩm không biến thể
                    </div>
                    <div class="d-flex align-items-center">
                        {{ $productsWithoutVariants->appends(request()->except('page2'))->links('vendor.pagination.adminPagi', ['pageName' => 'page2']) }}
                    </div>
                </div>
                @endif