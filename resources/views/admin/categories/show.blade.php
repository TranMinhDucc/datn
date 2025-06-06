@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="card p-4">
        <div class="card-header">
            <h3 class="text-center">Chi tiết danh mục</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Hình ảnh bên trái -->
                <div class="col-md-4 text-center">
                    <h5>Ảnh danh mục</h5>
                    @if($category->icon)
                        <img src="{{ asset('storage/' . $category->icon) }}" class="img-fluid rounded border mb-3" alt="Icon">
                    @else
                        <em>Không có ảnh</em>
                    @endif
                </div>

                <!-- Nội dung bên phải -->
                <div class="col-md-8">
                    <table class="table table-bordered">
                        
                        <tr>
                            <th>Tên danh mục</th>
                            <td>{{ $category->name }}</td>
                        </tr>
                        
                        <tr>
                            <th>Mô tả (Description)</th>
                            <td>{{ $category->description ?? 'Không có' }}</td>
                        </tr>
                       
                        <tr>
                            <th>Slug</th>
                            <td>{{ $category->slug }}</td>
                        </tr>
                      
                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                @if($category->status)
                                    <span class="badge bg-success">Hiển thị</span>
                                @else
                                    <span class="badge bg-danger">Ẩn</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ngày tạo</th>
                            <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Cập nhật</th>
                            <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Nút hành động -->
            <div class="text-center mt-4">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary">Chỉnh sửa</a>
            </div>
        </div>
    </div>
</div>
@endsection
