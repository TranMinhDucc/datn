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
                            'refunded' => 'Đã hoàn tiền',
                            'exchange_requested' => 'Khách yêu cầu đổi hàng',
                            'exchange_in_progress' => 'Đang xử lý đổi hàng',
                            'refund_processing' => 'Đang xử lý hoàn tiền',
                            'exchange_and_refund_processing' => 'Đang xử lý đổi & hoàn tiền',
                            'rejected_temp' => 'Tạm từ chối (một phần)',
                            'closed' => 'Đã đóng yêu cầu',
                        ];

                        $statusClass = [
                            'pending' => 'bg-warning',
                            'approved' => 'bg-primary',
                            'rejected' => 'bg-danger',
                            'refunded' => 'bg-success',
                            'exchange_requested' => 'bg-info',
                            'exchange_in_progress' => 'bg-info',
                            'refund_processing' => 'bg-purple text-white',
                            'exchange_and_refund_processing' => 'bg-teal text-white',
                            'rejected_temp' => 'bg-dark text-white',
                            'closed' => 'bg-secondary',
                        ];
                    @endphp

                    <span class="badge {{ $statusClass[$req->status] ?? 'bg-secondary' }}">
                        {{ $statusText[$req->status] ?? ucfirst($req->status) }}
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
