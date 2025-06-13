@extends('layouts.admin')

@section('title', 'Chỉnh sửa từ khóa')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Chỉnh sửa từ khóa bị cấm</h1>

    <form action="{{ route('admin.badwords.update', $badword) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="word" class="form-label">Từ khóa</label>
            <input type="text" name="word" id="word" class="form-control" value="{{ old('word', $badword->word) }}" required>
            @error('word')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.badwords.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
