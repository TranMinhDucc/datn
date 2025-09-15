@extends('layouts.admin')
@section('title', 'Tạo Banner')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content  flex-column-fluid ">
        <div id="kt_app_content_container" class="app-container  container-xxl ">
            <div class="card card-flush">
                <div class="card-header py-5">
                    <h3 class="card-title">Tạo Banner</h3>
                </div>

                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">

                        {{-- Subtitle --}}
                        <div class="mb-5">
                            <label class="form-label">Phụ đề (Subtitle)</label>
                            <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}">
                            @error('subtitle')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Title --}}
                        <div class="mb-5">
                            <label class="form-label">Tiêu đề (Title)</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                            @error('title')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-5">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" id="editor" class="form-control" rows="8">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Main Image --}}
                        <div class="mb-5">
                            <label class="form-label">Ảnh người mẫu chính</label>
                            <input type="file" name="main_image" class="form-control" accept="image/*">
                            @error('main_image')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- {{-- Sub Image 1 --}}
                        <h5 class="mb-3 mt-5">Ảnh phụ 1 (Sản phẩm 1)</h5>
                        <div class="mb-3">
                            <label class="form-label">Ảnh phụ 1</label>
                            <input type="file" name="sub_image_1" class="form-control" accept="image/*">
                            @error('sub_image_1')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tên sản phẩm 1</label>
                            <input type="text" name="sub_image_1_name" class="form-control" value="{{ old('sub_image_1_name') }}">
                            @error('sub_image_1_name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Giá sản phẩm 1</label>
                            <input type="number" step="0.01" name="sub_image_1_price" class="form-control" value="{{ old('sub_image_1_price') }}">
                            @error('sub_image_1_price')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Sub Image 2 --}}
                        <h5 class="mb-3 mt-5">Ảnh phụ 2 (Sản phẩm 2)</h5>
                        <div class="mb-3">
                            <label class="form-label">Ảnh phụ 2</label>
                            <input type="file" name="sub_image_2" class="form-control" accept="image/*">
                            @error('sub_image_2')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tên sản phẩm 2</label>
                            <input type="text" name="sub_image_2_name" class="form-control" value="{{ old('sub_image_2_name') }}">
                            @error('sub_image_2_name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Giá sản phẩm 2</label>
                            <input type="number" step="0.01" name="sub_image_2_price" class="form-control" value="{{ old('sub_image_2_price') }}">
                            @error('sub_image_2_price')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div> -->

                        {{-- Product pickers --}}
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
                                        {{ (string)old('product_id_1') === (string)$p->id ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div id="preview_1" class="d-flex align-items-center gap-2 mt-2"></div>
                                @error('product_id_1') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Sản phẩm 2</label>
                                <select name="product_id_2" id="product_id_2" class="product-select">
                                    <option value="">-- Không chọn --</option>
                                    @foreach ($products as $p)
                                    <option value="{{ $p->id }}"
                                        data-image="{{ $p->image_url }}"
                                        data-name="{{ $p->name }}"
                                        {{ (string)old('product_id_2') === (string)$p->id ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div id="preview_2" class="d-flex align-items-center gap-2 mt-2"></div>
                                @error('product_id_2') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Order and Language --}}
                            <div class="row mb-5">
                                {{-- <div class="col-md-6">
                                <label class="form-label">Thứ tự hiển thị</label>
                                <input type="number" name="thu_tu" class="form-control" value="{{ old('thu_tu', 0) }}">
                                @error('thu_tu')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div> --}}
                            {{-- <div class="col-md-6">
                                <label class="form-label">Ngôn ngữ</label>
                                <select name="ngon_ngu" class="form-select">
                                    <option value="vi" {{ old('ngon_ngu') == 'vi' ? 'selected' : '' }}>Tiếng Việt</option>
                            <option value="en" {{ old('ngon_ngu') == 'en' ? 'selected' : '' }}>English</option>
                            </select>
                            @error('ngon_ngu')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div> --}}
                    </div>

                    {{-- Status --}}
                    <div class="form-check form-switch mb-5">
                        <input type="checkbox" name="status" class="form-check-input" id="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Ẩn hiện</label>
                    </div>

                    {{-- Buttons --}}
                    <hr>
                    {{-- <h5 class="mb-3">Nút bấm (Buttons)</h5>
                        <div id="button-container">
                            @php $oldButtons = old('buttons', []); @endphp
                            @if (!empty($oldButtons))
                                @foreach ($oldButtons as $i => $btn)
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <input type="text" name="buttons[{{ $i }}][ten]" class="form-control"
                    value="{{ $btn['ten'] ?? '' }}" placeholder="Tên nút (VD: Mua ngay)">
                    @error("buttons.$i.ten")
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
            </div>
            <div class="col-md-5">
                <input type="text" name="buttons[{{ $i }}][duong_dan]" class="form-control"
                    value="{{ $btn['duong_dan'] ?? '' }}" placeholder="Link nút (http://...)">
                @error("buttons.$i.duong_dan")
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
        @endforeach
        @else
        <div class="row mb-3">
            <div class="col-md-5">
                <input type="text" name="buttons[0][ten]" class="form-control" placeholder="Tên nút (VD: Mua ngay)">
            </div>
            <div class="col-md-5">
                <input type="text" name="buttons[0][duong_dan]" class="form-control" placeholder="Link nút (http://...)">
            </div>
        </div>
        @endif
    </div>

    <button type="button" class="btn btn-sm btn-light-primary" onclick="addButton()">➕ Thêm nút</button> --}}
</div>

<div class="card-footer d-flex justify-content-end">
    <a href="{{ route('admin.banners.index') }}" class="btn btn-light me-2">Huỷ</a>
    <button type="submit" class="btn btn-primary">Lưu Banner</button>
</div>
</form>
</div>
</div>
</div>
</div>

{{-- Select2 (text-only) + Preview ảnh bên dưới --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  // KHÔNG dùng class "form-select" của Bootstrap để tránh xung đột chiều cao
  $('.product-select').select2({ width: '100%', placeholder: 'Chọn sản phẩm' });

  function renderPreview(selectId, previewId){
    const opt = document.querySelector(`#${selectId} option:checked`);
    const wrap = document.getElementById(previewId);
    if (!opt || !opt.value) { wrap.innerHTML = ''; return; }
    const img  = opt.getAttribute('data-image') || '';
    const name = opt.getAttribute('data-name')  || opt.textContent;
    wrap.innerHTML = `
      <img src="${img}" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">
      <div class="text-truncate" style="max-width:260px">${name}</div>
    `;
  }

  $('#product_id_1').on('change', () => renderPreview('product_id_1', 'preview_1'));
  $('#product_id_2').on('change', () => renderPreview('product_id_2', 'preview_2'));

  // Render lần đầu theo old()
  renderPreview('product_id_1', 'preview_1');
  renderPreview('product_id_2', 'preview_2');
</script>

<!-- <script>
    let buttonIndex = {
        {
            count(old('buttons', [0]))
        }
    };

    function addButton() {
        const container = document.getElementById('button-container');
        const html = `
            <div class="row mb-3">
                <div class="col-md-5">
                    <input type="text" name="buttons[${buttonIndex}][ten]" class="form-control" placeholder="Tên nút">
                </div>
                <div class="col-md-5">
                    <input type="text" name="buttons[${buttonIndex}][duong_dan]" class="form-control" placeholder="Link nút">
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        buttonIndex++;
    }
</script> -->

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                    'insertTable', 'mediaEmbed', 'imageUpload', '|',
                    'undo', 'redo'
                ]
            },
            language: 'vi'
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endsection