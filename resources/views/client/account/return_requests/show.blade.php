@extends('layouts.client')

@section('title', 'Chi tiết yêu cầu Hoàn / Đổi hàng')

@section('content')
    <div class="container py-5">
        <h3 class="fw-bold mb-4">📦 Chi tiết yêu cầu Hoàn / Đổi hàng</h3>

        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Đơn hàng:</strong> {{ $returnRequest->order->order_code }}</p>
                <p><strong>Loại:</strong> {{ $returnRequest->type === 'return' ? 'Hoàn hàng' : 'Đổi hàng' }}</p>
                <p><strong>Lý do:</strong> {{ $returnRequest->reason }}</p>
                <p><strong>Ngày gửi:</strong> {{ $returnRequest->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Trạng thái:</strong>
                    @php
                        $statusText = [
                            'pending' => 'Đang chờ xử lý',
                            'approved' => 'Đã chấp nhận',
                            'rejected' => 'Đã từ chối',
                            'exchanged' => 'Đã đổi hàng',
                        ];
                        $statusClass = [
                            'pending' => 'bg-warning',
                            'approved' => 'bg-success',
                            'rejected' => 'bg-danger',
                            'exchanged' => 'bg-info',
                        ];
                    @endphp
                    <span class="badge {{ $statusClass[$returnRequest->status] ?? 'bg-secondary' }}">
                        {{ $statusText[$returnRequest->status] ?? $returnRequest->status }}
                    </span>
                </p>
            </div>
        </div>


        <h5 class="fw-semibold">📋 Danh sách sản phẩm:</h5>
        <ul class="list-group mb-4">
            @foreach ($returnRequest->items as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $item->orderItem->product_name ?? 'Sản phẩm' }}
                    <span class="badge bg-primary">x{{ $item->quantity }}</span>
                </li>
            @endforeach
        </ul>

        <h5 class="fw-semibold mb-3">📎 File đính kèm:</h5>
        @php
            $files = json_decode($returnRequest->attachments ?? '[]', true);
        @endphp

        @if (!empty($files))
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach ($files as $file)
                        @php
                            $ext = pathinfo($file, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                            $isVideo = in_array(strtolower($ext), ['mp4', 'webm']);
                        @endphp
                        <div class="swiper-slide text-center">
                            @if ($isImage)
                                <img src="{{ asset('storage/' . $file) }}" class="img-fluid rounded shadow"
                                    style="max-height: 400px">
                            @elseif ($isVideo)
                                <video controls class="w-100 rounded">
                                    <source src="{{ asset('storage/' . $file) }}" type="video/{{ $ext }}">
                                    Trình duyệt không hỗ trợ video.
                                </video>
                            @else
                                <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-outline-primary">
                                    📄 Tải file
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Nút điều hướng -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>

                <!-- Dots -->
                <div class="swiper-pagination mt-3"></div>
            </div>
        @else
            <p>📁 Không có file đính kèm.</p>
        @endif

        <a href="{{ route('client.account.return_requests.index') }}" class="btn btn-secondary mt-4">← Quay lại danh
            sách</a>
    </div>
@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.mySwiper', {
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });
    </script>

@endsection
