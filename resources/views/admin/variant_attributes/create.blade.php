@extends('layouts.admin')
@section('content')
<div class="container">
    <h3 class="mb-4">🧩 Thêm Nhiều Thuộc Tính & Giá Trị</h3>

<form action="{{ route('admin.variant_attributes.store') }}" method="POST">
    @csrf

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="attribute-fields">
        <div class="attribute-group border rounded p-3 mb-3">
            <input type="text" name="attributes[0][name]" class="form-control mb-2" placeholder="Tên thuộc tính" required>
            <input type="text" name="attributes[0][values]" class="form-control mb-2" placeholder="Giá trị: Red|Green|Blue" required>
            <button type="button" class="btn btn-sm btn-danger remove-attribute">Xóa thuộc tính</button>
        </div>
    </div>

    <button type="button" class="btn btn-outline-primary mb-3" onclick="addAttribute()">+ Thêm thuộc tính khác</button>
    <br>
    <button type="submit" class="btn btn-primary">Lưu tất cả</button>
</form>

<script>
    let attributeIndex = 1;

    function addAttribute() {
        const wrapper = document.getElementById('attribute-fields');
        const html = `
        <div class="attribute-group border rounded p-3 mb-3">
            <input type="text" name="attributes[${attributeIndex}][name]" class="form-control mb-2" placeholder="Tên thuộc tính" required>
            <input type="text" name="attributes[${attributeIndex}][values]" class="form-control mb-2" placeholder="Giá trị: S|M|L" required>
            <button type="button" class="btn btn-sm btn-danger remove-attribute">Xóa thuộc tính</button>
        </div>`;
        wrapper.insertAdjacentHTML('beforeend', html);
        attributeIndex++;
    }

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-attribute')) {
            e.target.closest('.attribute-group').remove();
        }
    });
</script>

@endsection