@extends('layouts.admin')
@section('title', 'Thùng rác sản phẩm')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">

        <div class="container-xxl">
            <div class="card">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <h3 class="card-title">Danh sách sản phẩm đã xóa</h3>

                    <div class="card-toolbar">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-light">
                            <i class="fa-solid fa-arrow-left me-2"></i> Quay lại danh sách
                        </a>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center min-w-60px">ID</th>
                                    <th class="text-center min-w-100px">Ảnh</th>
                                    <th class="min-w-150px">Tên sản phẩm</th>
                                    <th class="text-center min-w-100px">Giá</th>
                                    <th class="text-center min-w-90px">Kho</th>
                                    <th class="text-center min-w-120px">Thời gian xóa</th>
                                    <th class="text-center min-w-150px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td class="text-center">{{ $product->id }}</td>
                                        <td class="text-center">
                                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/80' }}"
                                                 alt="{{ $product->name }}" width="60" height="60"
                                                 style="object-fit: cover;" class="rounded">
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td class="text-center">{{ number_format($product->sale_price ?? 0, 0, ',', '.') }} đ</td>
                                        <td class="text-center">{{ $product->stock_quantity }}</td>
                                        <td class="text-center">{{ $product->deleted_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Khôi phục">
                                                    <i class="fa-solid fa-rotate-left"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.products.forceDelete', $product->id) }}" method="POST" class="d-inline-block"
                                                  onsubmit="return confirm('Xóa vĩnh viễn sản phẩm này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Xoá vĩnh viễn">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Không có sản phẩm nào trong thùng rác.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
