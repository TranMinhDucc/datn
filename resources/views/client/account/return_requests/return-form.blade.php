@extends('layouts.client')

@section('title', 'Yêu cầu Hoàn / Đổi hàng')

@section('content')
    <div class="container py-5">
        <h3 class="mb-4 fw-bold">Yêu Cầu Hoàn / Đổi Hàng Cho Đơn Hàng #{{ $order->code }}</h3>

        <form action="{{ route('client.account.return_requests.store', $order->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="type" class="form-label">Hình thức</label>
                <select name="type" class="form-select" required>
                    <option value="return" {{ old('type') == 'return' ? 'selected' : '' }}>Hoàn hàng</option>
                    <option value="exchange" {{ old('type') == 'exchange' ? 'selected' : '' }}>Đổi hàng</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Chọn sản phẩm muốn {{ old('type') == 'exchange' ? 'đổi' : 'hoàn' }}</label>

                @foreach ($orderItems as $index => $item)
                    <div class="border rounded p-3 mb-3">
                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">

                        <label>{{ $item->product_name }} (Tối đa: {{ $item->quantity }})</label>
                        <input type="number" name="items[{{ $index }}][quantity]"
                            value="{{ old("items.$index.quantity") }}" min="0" max="{{ $item->quantity }}"
                            class="form-control w-25" required
                            oninvalid="this.setCustomValidity('Vui lòng nhập số lượng không vượt quá {{ $item->quantity }}')"
                            oninput="this.setCustomValidity('')">
                    </div>
                @endforeach

            </div>

            <div class="mb-3">
                <label for="reason" class="form-label">Lý do</label>
                <textarea name="reason" class="form-control" rows="4" required>{{ old('reason') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh / Video đính kèm (tối đa 5 tệp)</label>
                <input type="file" name="attachments[]" class="form-control" multiple accept="image/*,video/*">
                @error('attachments.0')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
                <small class="text-muted">Hỗ trợ ảnh và video (MP4, WEBM...). Mỗi file tối đa 5MB.</small>
            </div>

            <button type="submit" class="btn btn-solid-danger btn-danger">Gửi yêu cầu</button>
            <a href="{{ route('client.account.dashboard') }}" class="btn btn-outline-secondary ms-2">Quay lại</a>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');

            checkboxes.forEach((checkbox) => {
                const wrapper = checkbox.closest('.border');
                const quantityInput = wrapper.querySelector('.quantity-input');

                // ✅ Khi load trang
                if (checkbox.checked) {
                    quantityInput.disabled = false;
                    quantityInput.classList.remove('opacity-50');
                }

                // ✅ Khi người dùng click checkbox
                checkbox.addEventListener('change', function() {
                    if (checkbox.checked) {
                        quantityInput.disabled = false;
                        quantityInput.classList.remove('opacity-50');
                    } else {
                        quantityInput.disabled = true;
                        quantityInput.value = '';
                        quantityInput.classList.add('opacity-50');
                    }
                });
            });

            // ✅ Trước khi submit: bật lại mọi input số lượng (để browser gửi dữ liệu đầy đủ)
            document.querySelector('form').addEventListener('submit', function(e) {
                document.querySelectorAll('.quantity-input:disabled').forEach(input => {
                    input.disabled = false;
                });

                const input = document.querySelector('input[name="attachments[]"]');
                const maxFiles = 5;

                if (input && input.files.length > maxFiles) {
                    e.preventDefault();
                    alert(`Bạn chỉ có thể tải lên tối đa ${maxFiles} tệp.`);
                }
            });
        });
    </script>
@endpush
