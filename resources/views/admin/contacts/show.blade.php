@extends('layouts.admin')

@section('title', 'Chi tiết liên hệ')

@section('content')
<div class="container mt-5">
    <div class="card p-4">
        <div class="card-header">
            <h3 class="text-center">Chi tiết liên hệ</h3>
        </div>
        <div class="card-body pt-0">
            {{-- Thông báo flash --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Thông tin liên hệ --}}
            <div class="mb-4">
                <div class="d-flex mb-3">
                    <div class="fw-bold me-3" style="width: 150px;">Họ tên:</div>
                    <div>{{ $contact->name }}</div>
                </div>
                <div class="d-flex mb-3">
                    <div class="fw-bold me-3" style="width: 150px;">Email:</div>
                    <div>{{ $contact->email }}</div>
                </div>
                <div class="d-flex mb-3">
                    <div class="fw-bold me-3" style="width: 150px;">Điện thoại:</div>
                    <div>{{ $contact->phone }}</div>
                </div>
                <div class="d-flex mb-3">
                    <div class="fw-bold me-3" style="width: 150px;">Tiêu đề:</div>
                    <div>{{ $contact->subject }}</div>
                </div>
                <div class="d-flex mb-3">
                    <div class="fw-bold me-3" style="width: 150px;">Nội dung:</div>
                    <div>{{ $contact->message }}</div>
                </div>
                <div class="d-flex mb-3">
                    <div class="fw-bold me-3" style="width: 150px;">Trạng thái:</div>
                    <div>
                        @if($contact->statusreply == '0')
                            <span class="badge bg-warning text-dark">Chưa xử lý</span>
                        @else
                            <span class="badge bg-success">Đã xử lý</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Form phản hồi --}}
        
@if(!$contact->statusreply)
<form method="POST" action="{{ route('admin.contacts.reply', $contact->id) }}">
    @csrf
 <div class="max-w-4xl mx-auto"> <!-- Tăng giới hạn container -->
    <textarea
        name="replyContent"
        required
        class="w-full h-40 p-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Nhập nội dung phản hồi tại đây..."
    > Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất.</textarea>
</div>


<button
    type="submit"
    class="mt-4 px-5 btn btn-primary py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
>
    Gửi phản hồi
</button>

</form>
@else
<p><strong>Đã phản hồi vào:</strong> {{ \Carbon\Carbon::parse($contact->replied_at)->format('d/m/Y H:i') }}</p>
@endif
            {{-- Nút quay lại --}}
            <div class="text-center mt-4">
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
