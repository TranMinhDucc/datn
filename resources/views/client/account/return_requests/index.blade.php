@extends('layouts.client')

@section('title', 'Lịch sử khiếu nại')

@section('content')
    <div class="container py-5">
        <h3 class="fw-bold mb-4">📋 Lịch sử Hoàn / Đổi Hàng</h3>

        @forelse($requests as $req)
            <div class="card mb-3">
                <div class="card-body">
                    <strong>Đơn hàng: </strong> #{{ $req->order->order_code }} <br>
                    <strong>Loại: </strong> {{ $req->type === 'return' ? 'Hoàn hàng' : 'Đổi hàng' }} <br>
                    <strong>Ngày gửi: </strong> {{ $req->created_at->format('d/m/Y') }} <br>
                    <strong>Trạng thái: </strong>
                    @php
                        $statusText = [
                            'pending' => 'Đang chờ xử lý',
                            'approved' => 'Đã chấp nhận',
                            'rejected' => 'Đã từ chối',
                            'exchanged' => 'Đã đổi hàng',
                        ];
                    @endphp
                    <span
                        class="badge 
                {{ $req->status === 'pending' ? 'bg-warning' : ($req->status === 'approved' ? 'bg-success' : 'bg-danger') }}">
                        {{ $statusText[$req->status] ?? $req->status }}
                    </span>

                    <div class="mt-3 text-end">
                        <a href="{{ route('client.account.return_requests.show', $req->id) }}"
                            class="btn btn-outline-dark btn-sm">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p>Không có yêu cầu nào.</p>
        @endforelse


        {{ $requests->links() }}
    </div>
@endsection
