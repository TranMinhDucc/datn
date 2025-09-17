@php
use Illuminate\Support\Str;

// ----- Bản đồ tiếng Việt -----
$statusMap = [
'open' => 'Đang mở',
'waiting_staff' => 'Chờ nhân viên',
'waiting_customer' => 'Chờ khách phản hồi',
'resolved' => 'Đã xử lý',
'closed' => 'Đã đóng',
];

$statusColor = [
'open' => '#f59e0b', // vàng
'waiting_staff' => '#64748b', // xám xanh
'waiting_customer' => '#0ea5e9', // xanh dương nhạt
'resolved' => '#10b981', // xanh lá
'closed' => '#334155', // slate
];

$priorityMap = [
'low' => 'Thấp',
'normal' => 'Bình thường',
'high' => 'Cao',
'urgent' => 'Khẩn cấp',
];

$categoryMap = [
'order' => 'Đơn hàng & vận chuyển',
'product' => 'Sản phẩm & chất lượng',
'payment' => 'Thanh toán & hoá đơn',
'account' => 'Tài khoản & đăng nhập',
'other' => 'Khác',
];

$badgeBg = $statusColor[$ticket->status] ?? '#64748b';
@endphp

@extends('layouts.admin')

@section('content')
<style>
    .card-clean {
        border: 1px solid #eef2f7;
        border-radius: 16px;
        box-shadow: 0 10px 24px rgba(0, 0, 0, .06);
    }

    .badge-soft {
        display: inline-block;
        padding: .35rem .6rem;
        border-radius: 999px;
        color: #fff;
        font-weight: 700;
        font-size: .8rem
    }

    .chat-area {
        max-height: 520px;
        overflow: auto;
        padding-right: 4px
    }

    .msg-row {
        display: flex;
        margin-bottom: 16px;
        gap: 10px
    }

    .msg-row.left {
        justify-content: flex-start
    }

    .msg-row.right {
        justify-content: flex-end
    }

    .msg {
        max-width: 68%;
        background: #fff;
        border: 1px solid #eff2f6;
        border-radius: 14px;
        padding: 10px 12px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, .05)
    }

    .msg .meta {
        font-size: .84rem;
        color: #6b7280;
        margin-bottom: 4px
    }

    .msg .body {
        white-space: pre-wrap
    }

    .msg-files {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 6px
    }

    .msg-files img {
        width: 110px;
        height: 110px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #eee
    }

    .side-label {
        color: #64748b;
        font-size: .86rem
    }

    .form-select,
    .form-control {
        border-radius: 10px
    }

    .btn-primary {
        background: #0ea5e9;
        border-color: #0ea5e9
    }

    .btn-success {
        background: #10b981;
        border-color: #10b981
    }
</style>
<div class="container-xxl px-3 px-lg-5 py-4">
    <div class="row g-4">
        {{-- Cột trái: hội thoại + trả lời --}}
        <div class="col-lg-8">
            <div class="card-clean p-4">

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <h5 class="mb-1">#{{ $ticket->id }} — {{ $ticket->subject }}</h5>
                        <div class="text-muted" style="font-size:.92rem">
                            KH: <strong>{{ $ticket->user->fullname ?? $ticket->user->username ?? $ticket->user->email }}</strong>
                            — Email: {{ $ticket->user->email }}
                        </div>
                    </div>
                    <span class="badge-soft" style="background:{{ $ticket->status_color }}">
                        {{ $ticket->status_label }}
                    </span>
                </div>

                {{-- tin nhắn --}}
                <div class="chat-area" id="msgBox">
                    @forelse(($ticket->messages ?? collect()) as $m)
                    @php
                    $isAdmin = (bool) $m->is_staff;
                    $senderName = $m->user->fullname ?? ($isAdmin ? 'Nhân viên' : 'Khách hàng');
                    @endphp
                    <div class="msg-row {{ $isAdmin ? 'right' : 'left' }}">
                        <div class="msg">
                            <div class="meta">
                                <strong>{{ $senderName }}</strong>
                                <span>• {{ $m->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="body">{!! nl2br(e($m->body)) !!}</div>

                            @if($m->attachments && $m->attachments->count())
                            <div class="msg-files">
                                @foreach($m->attachments as $att)
                                @if(Str::startsWith($att->mime, 'image/'))
                                <a href="{{ $att->url }}" target="_blank"><img src="{{ $att->url }}" alt=""></a>
                                @else
                                <a href="{{ $att->url }}" target="_blank" class="btn btn-sm btn-light border">
                                    📄 {{ $att->original_name }}
                                </a>
                                @endif
                                @endforeach
                            </div>
                            @endif

                        </div>
                    </div>
                    @empty
                    <div class="text-muted">Chưa có trao đổi.</div>
                    @endforelse
                </div>

                {{-- form trả lời --}}
                <form class="mt-3" method="POST" action="{{ route('admin.support.tickets.reply', $ticket) }}" enctype="multipart/form-data">
                    @csrf
                    <textarea name="body" class="form-control mb-2" rows="4" placeholder="Nhập nội dung trả lời..."></textarea>
                    <input type="file" name="attachments[]" multiple class="form-control mb-3" />
                    <div class="d-flex gap-2">
                        <button class="btn btn-success">Gửi trả lời</button>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.support.tickets.index') }}">Quay lại danh sách</a>
                    </div>
                </form>

            </div>
        </div>

        {{-- Cột phải: thông tin phiếu / cập nhật --}}
        <div class="col-lg-4">
            <div class="card-clean p-4">
                <h6 class="mb-3">Thông tin phiếu</h6>

                <div class="mb-2"><span class="side-label">Nhóm:</span>
                    <div><strong>{{ \App\Models\SupportTicket::categoryMap()[$ticket->category] ?? $ticket->category }}</strong></div>
                </div>

                <div class="mb-2"><span class="side-label">Mã đơn:</span>
                    <div>{{ $ticket->order_code ?: '—' }}</div>
                </div>

                <div class="mb-2"><span class="side-label">Vận đơn:</span>
                    <div>{{ $ticket->carrier_code ?: '—' }}</div>
                </div>

                <div class="mb-2"><span class="side-label">Tạo lúc:</span>
                    <div>{{ $ticket->created_at?->format('d/m/Y H:i') }}</div>
                </div>

                <div class="mb-3"><span class="side-label">Cập nhật:</span>
                    <div>{{ $ticket->updated_at?->format('d/m/Y H:i') }}</div>
                </div>

                <form method="POST" action="{{ route('admin.support.tickets.update', $ticket) }}">
                    @csrf @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            @foreach(\App\Models\SupportTicket::statusMap() as $key=>$label)
                            <option value="{{ $key }}" @selected($ticket->status === $key)>{{ $label }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ưu tiên</label>
                        <select name="priority" class="form-select">
                            @foreach(\App\Models\SupportTicket::priorityMap() as $key=>$label)
                            <option value="{{ $key }}" @selected($ticket->priority === $key)>{{ $label }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Gán cho</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">— Không gán —</option>
                            @foreach($agents as $ag)
                            <option value="{{ $ag->id }}" @selected($ticket->assigned_to == $ag->id)>
                                {{ $ag->name ?? $ag->fullname ?? $ag->email }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary w-100">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // auto scroll xuống cuối để thấy tin mới
    const box = document.getElementById('msgBox');
    if (box) box.scrollTop = box.scrollHeight;
</script>
@endsection