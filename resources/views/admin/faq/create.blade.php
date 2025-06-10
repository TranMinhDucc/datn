@extends('layouts.admin')

@section('title', 'FAQ')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm câu hỏi mới</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('faq.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Câu hỏi</label>
                <input type="text" name="question" class="form-control" value="{{ old('question') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Trả lời</label>
                <textarea name="answer" class="form-control" rows="4" required>{{ old('answer') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('faq.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>

@endsection