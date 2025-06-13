@extends('layouts.admin')

@section('title', 'Thêm từ khóa')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Thêm từ khóa bị cấm</h1>

    <form action="{{ route('admin.badwords.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="word" class="form-label">Từ khóa</label>
            <input type="text" name="word" id="word" class="form-control" value="{{ old('word') }}" required>
            @error('word')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.badwords.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
