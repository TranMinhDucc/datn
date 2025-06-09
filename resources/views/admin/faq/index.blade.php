@extends('layouts.admin')

@section('title', 'FAQ')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Danh sách câu hỏi thường gặp (FAQ)</h3>
        <a href="{{ route('faq.create') }}" class="btn btn-primary">Thêm mới</a>
    </div>

    <div class="card-body">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Câu hỏi</th>
                    <th>Trả lời</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($faqs as $faq)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $faq->question }}</td>
                        <td>{{ $faq->answer }}</td>
                        <td>{{ $faq->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('faq.edit', $faq->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('faq.destroy', $faq->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa câu hỏi này?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                @if($faqs->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center">Chưa có câu hỏi nào.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection