@extends('layouts.admin')
@section('title', 'Chỉnh sửa Banner')

@section('content')
<div class="card card-flush">
    <div class="card-header py-5">
        <h3 class="card-title">Chỉnh sửa Banner</h3>
    </div>

    <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">
            {{-- Subtitle --}}
            <div class="mb-5">
                <label class="form-label">Phụ đề (subtitle)</label>
                <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $banner->subtitle) }}">
                @error('subtitle')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Title --}}
            <div class="mb-5">
                <label class="form-label">Tiêu đề (title)</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}">
                @error('title')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-5">
                <label class="form-label">Mô tả (description)</label>
                <textarea name="description" id="editor" class="form-control" rows="6">{{ old('description', $banner->description) }}</textarea>
                @error('description')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Main Image --}}
            <div class="mb-5">
                <label class="form-label">Ảnh chính (main_image)</label><br>
                @if($banner->main_image)
                <img src="{{ asset('storage/' . $banner->main_image) }}" width="150" class="mb-3">
                @endif
                <input type="file" name="main_image" class="form-control" accept="image/*">
                @error('main_image')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- {{-- Sub Image 1 --}}
            <div class="mb-5">
                <label class="form-label">Ảnh phụ 1 (sub_image_1)</label><br>
                @if($banner->sub_image_1)
                    <img src="{{ asset('storage/' . $banner->sub_image_1) }}" width="150" class="mb-3">
                @endif
                <input type="file" name="sub_image_1" class="form-control" accept="image/*">
                <input type="text" name="sub_image_1_name" class="form-control mt-2" placeholder="Tên sản phẩm" value="{{ old('sub_image_1_name', $banner->sub_image_1_name) }}">
                <input type="number" step="0.01" name="sub_image_1_price" class="form-control mt-2" placeholder="Giá sản phẩm" value="{{ old('sub_image_1_price', $banner->sub_image_1_price) }}">
            </div>

            {{-- Sub Image 2 --}}
            <div class="mb-5">
                <label class="form-label">Ảnh phụ 2 (sub_image_2)</label><br>
                @if($banner->sub_image_2)
                    <img src="{{ asset('storage/' . $banner->sub_image_2) }}" width="150" class="mb-3">
                @endif
                <input type="file" name="sub_image_2" class="form-control" accept="image/*">
                <input type="text" name="sub_image_2_name" class="form-control mt-2" placeholder="Tên sản phẩm" value="{{ old('sub_image_2_name', $banner->sub_image_2_name) }}">
                <input type="number" step="0.01" name="sub_image_2_price" class="form-control mt-2" placeholder="Giá sản phẩm" value="{{ old('sub_image_2_price', $banner->sub_image_2_price) }}">
            </div> -->
            {{-- Product pickers --}}
            <div class="row mb-5">
                <div class="col-md-6">
                    <label class="form-label">Sản phẩm 1</label>
                    <select name="product_id_1" id="product_id_1" class="product-select">
                        <option value="">-- Không chọn --</option>
                        @foreach ($products as $p)
                        <option value="{{ $p->id }}"
                            data-image="{{ $p->image_url }}"
                            data-name="{{ $p->name }}"
                            {{ (string)old('product_id_1', $banner->product_id_1) === (string)$p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                        @endforeach
                    </select>
                    <div id="preview_1" class="d-flex align-items-center gap-2 mt-2"></div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Sản phẩm 2</label>
                    <select name="product_id_2" id="product_id_2" class="product-select">
                        <option value="">-- Không chọn --</option>
                        @foreach ($products as $p)
                        <option value="{{ $p->id }}"
                            data-image="{{ $p->image_url }}"
                            data-name="{{ $p->name }}"
                            {{ (string)old('product_id_2', $banner->product_id_2) === (string)$p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                        @endforeach
                    </select>
                    <div id="preview_2" class="d-flex align-items-center gap-2 mt-2"></div>
                </div>
            </div>
            {{-- Button title & link --}}
            <div class="row mb-5">
                <div class="col-md-6">
                    <label class="form-label">Tiêu đề nút (btn_title)</label>
                    <input type="text"
                        name="btn_title"
                        class="form-control"
                        value="{{ old('btn_title', $banner->btn_title ?? 'Shop Now') }}"
                        placeholder="VD: Mua ngay">
                    @error('btn_title') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Link nút (btn_link)</label>
                    <input type="url"
                        name="btn_link"
                        class="form-control"
                        value="{{ old('btn_link', $banner->btn_link) }}"
                        placeholder="https://... hoặc /duong-dan">
                    @error('btn_link') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            {{-- Trạng thái hiển thị --}}
            <div class="form-check form-switch mb-5">
                <input type="checkbox" name="status" class="form-check-input" id="status"
                    value="1" {{ old('status', $banner->status) ? 'checked' : '' }}>
                <label class="form-check-label" for="status">Ẩn hiện</label>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
            <a href="{{ route('admin.banners.index') }}" class="btn btn-light me-2">Huỷ</a>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </div>
    </form>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            language: 'vi',
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo']
        })
        .catch(error => {
            console.error(error);
        });
</script>
{{-- product select--}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // KHÔNG để class "form-select" (Bootstrap) trên <select> – dễ xung đột chiều cao
    $('.product-select').select2({
        width: '100%',
        placeholder: 'Chọn sản phẩm'
    });

    function renderPreview(selectId, previewId) {
        const opt = document.querySelector(`#${selectId} option:checked`);
        const wrap = document.getElementById(previewId);
        if (!opt || !opt.value) {
            wrap.innerHTML = '';
            return;
        }
        const img = opt.getAttribute('data-image') || '';
        const name = opt.getAttribute('data-name') || opt.textContent;
        wrap.innerHTML = `
      <img src="${img}" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">
      <div class="text-truncate" style="max-width:260px">${name}</div>
    `;
    }

    $('#product_id_1').on('change', () => renderPreview('product_id_1', 'preview_1'));
    $('#product_id_2').on('change', () => renderPreview('product_id_2', 'preview_2'));

    // Render lần đầu khi mở trang edit
    renderPreview('product_id_1', 'preview_1');
    renderPreview('product_id_2', 'preview_2');
</script>
@endsection