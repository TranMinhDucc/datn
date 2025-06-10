@extends('layouts.admin')
@section('title', 'Chỉnh sửa Banner')
@section('content')
    <div class="card card-flush">
        <div class="card-header py-5">
            <h3 class="card-title">Edit Banner</h3>
        </div>

        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="mb-5">
                    <label class="form-label">Tên banner</label>
                    <input type="text" name="ten" class="form-control" value="{{ old('ten', $banner->ten) }}">
                    @error('ten')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="form-label">Hình ảnh hiện tại</label><br>
                    <img src="{{ $banner->hinh_anh }}" width="150" class="mb-3">
                    <input type="file" name="hinh_anh" class="form-control" accept="image/*">
                    @error('hinh_anh')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="form-label">Mô tả</label>
                    <textarea name="mo_ta" id="editor" class="form-control"
                        rows="10">{{ old('mo_ta', $banner->mo_ta) }}</textarea>
                    @error('mo_ta')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-5">
                    <div class="col-md-4">
                        <label class="form-label">Thứ tự hiển thị</label>
                        <input type="number" name="thu_tu" class="form-control"
                            value="{{ old('thu_tu', $banner->thu_tu) }}">
                        @error('thu_tu')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ngôn ngữ</label>
                        <select name="ngon_ngu" class="form-select">
                            <option value="vi" {{ old('ngon_ngu', $banner->ngon_ngu) == 'vi' ? 'selected' : '' }}>Tiếng Việt
                            </option>
                            <option value="en" {{ old('ngon_ngu', $banner->ngon_ngu) == 'en' ? 'selected' : '' }}>English
                            </option>
                        </select>
                        @error('ngon_ngu')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-check form-switch mb-5">
                    <input type="checkbox" name="status" class="form-check-input" id="status" {{ old('status', $banner->status) ? 'checked' : '' }}>
                    <label class="form-check-label" for="status">Ẩn hiện</label>
                </div>
                <hr>
                <h5 class="mb-3">Nút bấm (Button)</h5>
                <div id="button-container">
                    @php
                        $oldButtons = old('buttons', $banner->buttons ?? []);
                    @endphp

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
                </div>

                <button type="button" class="btn btn-sm btn-light-primary" onclick="addButton()">➕ Thêm nút</button>
            </div>

            <div class="card-footer d-flex justify-content-end">
                <a href="{{ route('admin.banners.index') }}" class="btn btn-light me-2">Huỷ</a>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>

    <script>
        let buttonIndex = {{ count($oldButtons) ?: 1 }};
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
    </script>

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