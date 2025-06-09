@extends('layouts.admin')

@section('title', 'Quản lý Thương hiệu')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center rounded-top">
            <h4 class="mb-0 fw-bold text-uppercase">Danh sách Thương hiệu</h4>
            <a href="{{ route('admin.brands.create') }}" class="btn btn-light text-primary fw-semibold">
                <i class="fas fa-plus-circle me-1"></i> Thêm mới
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light text-center">
    <tr>
        <th style="width: 5%;">ID</th>
        <th>Tên thương hiệu</th>
        <th style="width: 15%;">Logo</th>
        <th style="width: 15%;">Trạng thái</th>
        <th style="width: 20%;">Hành động</th>
    </tr>
</thead>
<tbody>
    @forelse($brands as $brand)
    <tr>
        <td class="text-center">{{ $brand->id }}</td>
        <td>{{ $brand->name }}</td>
        <td class="text-center">
            @if($brand->logo)
                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}"
                    class="img-fluid rounded" style="max-height: 60px;">
            @else
                <span class="text-muted">Chưa có logo</span>
            @endif
        </td>
        <td class="text-center">
            @if ($brand->status)
                <span class="badge bg-success px-3 py-2">Công bố</span>
            @else
                <span class="badge bg-danger px-3 py-2">Chưa công bố</span>
            @endif
        </td>
        <td class="text-center">
            <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-sm btn-warning me-1">
                <i class="fas fa-edit me-1"></i> Sửa
            </a>
            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline-block"
                onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash-alt me-1"></i> Xóa
                </button>
            </form>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="text-center text-muted">Không có thương hiệu nào.</td>
    </tr>
    @endforelse
</tbody>

                </table>
            </div>
        </div>
    </div>
</div>
@endsection
