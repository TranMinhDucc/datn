@extends('layouts.admin')

@section('title', 'Từ khóa bị cấm')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Danh sách từ khóa bị cấm</h1>
        <div>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i> Quay về đánh giá
            </a>
        </div>
    </div>

    <a href="{{ route('admin.badwords.create') }}" class="btn btn-primary mb-3">+ Thêm từ khóa</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Từ khóa</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($badwords as $index => $word)
                <tr>
                    <td>{{ $badwords->firstItem() + $index }}</td>
                    <td>{{ $word->word }}</td>
                    <td>
                        <a href="{{ route('admin.badwords.edit', $word) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('admin.badwords.destroy', $word) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Bạn chắc chắn muốn xóa?')" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Chưa có từ khóa nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Phân trang --}}
    <div class="mt-3">
        {{ $badwords->links() }}
    </div>
</div>
@endsection
