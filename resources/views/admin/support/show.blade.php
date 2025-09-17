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
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --shadow-soft: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            --shadow-hover: 0 15px 35px rgba(31, 38, 135, 0.2);
            --border-radius: 20px;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow-soft);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .ticket-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            position: relative;
            overflow: hidden;
        }

        .ticket-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {

            0%,
            100% {
                opacity: 0.3;
            }

            50% {
                opacity: 0.8;
            }
        }

        .ticket-id {
            font-size: 1.75rem;
            font-weight: 800;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 0.5rem;
        }

        .customer-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            opacity: 0.9;
        }

        .avatar-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--success-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .chat-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            margin: 1.5rem 0;
            max-height: 600px;
            overflow: hidden;
        }

        .chat-area {
            max-height: 500px;
            overflow-y: auto;
            padding: 1.5rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }

        .chat-area::-webkit-scrollbar {
            width: 6px;
        }

        .chat-area::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-area::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .message-bubble {
            max-width: 75%;
            margin-bottom: 1.5rem;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-bubble.staff {
            margin-left: auto;
        }

        .message-bubble.customer {
            margin-right: auto;
        }

        .message-content {
            padding: 1.25rem 1.5rem;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .message-bubble.staff .message-content {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px 20px 5px 20px;
        }

        .message-bubble.customer .message-content {
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            border-radius: 20px 20px 20px 5px;
        }

        .message-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
            opacity: 0.8;
        }

        .sender-name {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .message-time {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .message-attachments {
            margin-top: 1rem;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .attachment-preview {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .attachment-preview:hover {
            transform: scale(1.05);
        }

        .attachment-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .file-attachment {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.2s ease;
            text-decoration: none;
            color: inherit;
        }

        .file-attachment:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .reply-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 0 0 15px 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-floating {
            position: relative;
        }

        .modern-input {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid transparent;
            border-radius: 15px;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            resize: vertical;
            min-height: 120px;
        }

        .modern-input:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            transform: translateY(-2px);
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
            margin: 1rem 0;
        }

        .file-input-modern {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }

        .file-input-label:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-gradient {
            background: var(--success-gradient);
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            transform: translate(-50%, -50%);
        }

        .btn-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(79, 172, 254, 0.6);
        }

        .btn-gradient:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-outline-modern {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: rgba(255, 255, 255, 0.9);
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .btn-outline-modern:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .info-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-radius: var(--border-radius);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .info-header {
            background: var(--warning-gradient);
            color: white;
            padding: 1.5rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            font-weight: 700;
            font-size: 1.1rem;
            text-align: center;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .info-body {
            padding: 2rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .info-value {
            font-weight: 700;
            text-align: right;
        }

        .form-select-modern {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            color: #333;
        }

        .form-select-modern:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            transform: translateY(-2px);
        }

        .form-label-modern {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .priority-badge {
            padding: 0.375rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .priority-low {
            background: linear-gradient(45deg, #4ade80, #22c55e);
        }

        .priority-normal {
            background: linear-gradient(45deg, #60a5fa, #3b82f6);
        }

        .priority-high {
            background: linear-gradient(45deg, #fb7185, #f43f5e);
        }

        .priority-urgent {
            background: linear-gradient(45deg, #f87171, #dc2626);
            animation: urgentPulse 1s ease-in-out infinite;
        }

        @keyframes urgentPulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(220, 38, 38, 0);
            }
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .floating-circle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent);
            animation: float 6s ease-in-out infinite;
        }

        .floating-circle:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 20%;
            left: 10%;
            animation-delay: -0s;
        }

        .floating-circle:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 10%;
            animation-delay: -2s;
        }

        .floating-circle:nth-child(3) {
            width: 80px;
            height: 80px;
            bottom: 20%;
            left: 20%;
            animation-delay: -4s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            33% {
                transform: translateY(-20px) rotate(120deg);
            }

            66% {
                transform: translateY(20px) rotate(240deg);
            }
        }

        @media (max-width: 768px) {
            .container-xxl {
                padding: 1rem !important;
            }

            .ticket-header {
                padding: 1.5rem 1rem;
            }

            .chat-area {
                padding: 1rem;
            }

            .message-bubble {
                max-width: 90%;
            }

            .reply-section {
                padding: 1.5rem 1rem;
            }

            .info-body {
                padding: 1.5rem 1rem;
            }
        }
    </style>

    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="container-xxl px-3 px-lg-5 py-4">
        <div class="row g-4">
            {{-- Cột trái: hội thoại + trả lời --}}
            <div class="col-lg-8">
                <div class="glass-card">
                    <div class="ticket-header">
                        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-placeholder">
                                    {{ substr($ticket->user->fullname ?? ($ticket->user->username ?? $ticket->user->email), 0, 1) }}
                                </div>
                                <div>
                                    <div class="ticket-id">#{{ $ticket->id }} — {{ $ticket->subject }}</div>
                                    <div class="customer-info">
                                        <span>👤
                                            {{ $ticket->user->fullname ?? ($ticket->user->username ?? $ticket->user->email) }}</span>
                                        <span>✉️ {{ $ticket->user->email }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="status-badge" style="background: {{ $badgeBg }}; color: white;">
                                <div class="status-dot"></div>
                                {{ $ticket->status_label }}
                            </div>
                        </div>
                    </div>

                    <div class="chat-container">
                        <div class="chat-area" id="msgBox">
                            @forelse(($ticket->messages ?? collect()) as $m)
                                @php
                                    $isAdmin = (bool) $m->is_staff;
                                    $senderName = $m->user->fullname ?? ($isAdmin ? 'Nhân viên' : 'Khách hàng');
                                @endphp
                                <div class="message-bubble {{ $isAdmin ? 'staff' : 'customer' }}">
                                    <div class="message-content">
                                        <div class="message-meta">
                                            <div class="sender-name">
                                                @if ($isAdmin)
                                                    🛡️ {{ $senderName }}
                                                @else
                                                    👤 {{ $senderName }}
                                                @endif
                                            </div>
                                            <div class="message-time">{{ $m->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                        <div class="message-body">{!! nl2br(e($m->body)) !!}</div>

                                        @if ($m->attachments && $m->attachments->count())
                                            <div class="message-attachments">
                                                @foreach ($m->attachments as $att)
                                                    @if (Str::startsWith($att->mime, 'image/'))
                                                        <a href="{{ $att->url }}" target="_blank"
                                                            class="attachment-preview">
                                                            <img src="{{ $att->url }}"
                                                                alt="{{ $att->original_name }}">
                                                        </a>
                                                    @else
                                                        <a href="{{ $att->url }}" target="_blank"
                                                            class="file-attachment">
                                                            📄 {{ $att->original_name }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center" style="color: rgba(255,255,255,0.7); padding: 3rem;">
                                    <div style="font-size: 3rem; margin-bottom: 1rem;">💬</div>
                                    <div>Chưa có trao đổi nào.</div>
                                </div>
                            @endforelse
                        </div>

                        <div class="reply-section">
                            <form method="POST" action="{{ route('admin.support.tickets.reply', $ticket) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-floating">
                                    <textarea name="body" class="modern-input w-100" placeholder="Nhập nội dung trả lời..." required></textarea>
                                </div>

                                <div class="file-input-wrapper">
                                    <input type="file" name="attachments[]" multiple class="file-input-modern"
                                        id="attachments">
                                    <label for="attachments" class="file-input-label">
                                        📎 Chọn tập tin đính kèm
                                    </label>
                                </div>

                                <div class="action-buttons">
                                    <button type="submit" class="btn-gradient">
                                        ✨ Gửi trả lời
                                    </button>
                                    <a href="{{ route('admin.support.tickets.index') }}" class="btn-outline-modern">
                                        ↩️ Quay lại danh sách
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột phải: thông tin phiếu / cập nhật --}}
            <div class="col-lg-4">
                <div class="info-card">
                    <div class="info-header">
                        📋 Thông tin phiếu
                    </div>
                    <div class="info-body">
                        <div class="info-item">
                            <span class="info-label">📂 Nhóm:</span>
                            <span
                                class="info-value">{{ \App\Models\SupportTicket::categoryMap()[$ticket->category] ?? $ticket->category }}</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">🛒 Mã đơn:</span>
                            <span class="info-value">{{ $ticket->order_code ?: '—' }}</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">🚚 Vận đơn:</span>
                            <span class="info-value">{{ $ticket->carrier_code ?: '—' }}</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">⏰ Tạo lúc:</span>
                            <span class="info-value">{{ $ticket->created_at?->format('d/m/Y H:i') }}</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">🔄 Cập nhật:</span>
                            <span class="info-value">{{ $ticket->updated_at?->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="info-card mt-4">
                    <div class="info-header">
                        ⚙️ Cập nhật trạng thái
                    </div>
                    <div class="info-body">
                        <form method="POST" action="{{ route('admin.support.tickets.update', $ticket) }}">
                            @csrf @method('PATCH')

                            <div class="mb-3">
                                <label class="form-label-modern">🔄 Trạng thái</label>
                                <select name="status" class="form-select-modern w-100">
                                    @foreach (\App\Models\SupportTicket::statusMap() as $key => $label)
                                        <option value="{{ $key }}" @selected($ticket->status === $key)>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-modern">⚡ Ưu tiên</label>
                                <select name="priority" class="form-select-modern w-100">
                                    @foreach (\App\Models\SupportTicket::priorityMap() as $key => $label)
                                        <option value="{{ $key }}" @selected($ticket->priority === $key)>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-modern">👥 Gán cho</label>
                                <select name="assigned_to" class="form-select-modern w-100">
                                    <option value="">— Không gán —</option>
                                    @foreach ($agents as $ag)
                                        <option value="{{ $ag->id }}" @selected($ticket->assigned_to == $ag->id)>
                                            {{ $ag->name ?? ($ag->fullname ?? $ag->email) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn-gradient w-100">
                                💾 Lưu thay đổi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto scroll xuống cuối để thấy tin mới
        const box = document.getElementById('msgBox');
        if (box) {
            box.scrollTop = box.scrollHeight;
        }

        // File input preview
        document.getElementById('attachments')?.addEventListener('change', function(e) {
            const label = document.querySelector('.file-input-label');
            const files = e.target.files;

            if (files.length > 0) {
                let fileText = files.length === 1 ? `📎 ${files[0].name}` : `📎 ${files.length} tập tin đã chọn`;
                label.innerHTML = fileText;
                label.style.background = 'rgba(79, 172, 254, 0.2)';
                label.style.borderColor = 'rgba(79, 172, 254, 0.5)';
            } else {
                label.innerHTML = '📎 Chọn tập tin đính kèm';
                label.style.background = 'rgba(255, 255, 255, 0.1)';
                label.style.borderColor = 'rgba(255, 255, 255, 0.3)';
            }
        });

        // Smooth scroll animation for new messages
        function smoothScrollToBottom() {
            if (box) {
                box.scrollTo({
                    top: box.scrollHeight,
                    behavior: 'smooth'
                });
            }
        }

        // Form validation with modern UI feedback
        const replyForm = document.querySelector('form[action*="reply"]');
        const textarea = document.querySelector('textarea[name="body"]');

        if (replyForm && textarea) {
            replyForm.addEventListener('submit', function(e) {
                if (textarea.value.trim() === '') {
                    e.preventDefault();

                    // Add error styling
                    textarea.style.borderColor = '#f87171';
                    textarea.style.boxShadow = '0 8px 25px rgba(248, 113, 113, 0.3)';
                    textarea.focus();

                    // Create floating error message
                    const errorMsg = document.createElement('div');
                    errorMsg.textContent = 'Vui lòng nhập nội dung trả lời';
                    errorMsg.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: linear-gradient(135deg, #f87171, #dc2626);
                    color: white;
                    padding: 1rem 1.5rem;
                    border-radius: 15px;
                    box-shadow: 0 10px 25px rgba(220, 38, 38, 0.4);
                    z-index: 9999;
                    animation: slideInRight 0.3s ease-out;
                    font-weight: 600;
                `;

                    document.body.appendChild(errorMsg);

                    setTimeout(() => {
                        errorMsg.remove();
                        textarea.style.borderColor = 'transparent';
                        textarea.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
                    }, 3000);

                    return false;
                }
            });
        }

        // Priority color mapping for dynamic updates
        const priorityColors = {
            'low': 'linear-gradient(45deg, #4ade80, #22c55e)',
            'normal': 'linear-gradient(45deg, #60a5fa, #3b82f6)',
            'high': 'linear-gradient(45deg, #fb7185, #f43f5e)',
            'urgent': 'linear-gradient(45deg, #f87171, #dc2626)'
        };

        // Add CSS animation keyframes dynamically
        const style = document.createElement('style');
        style.textContent = `
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes messageAppear {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    `;
        document.head.appendChild(style);

        // Add hover effects to message bubbles
        document.querySelectorAll('.message-bubble').forEach(bubble => {
            bubble.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.01)';
                this.style.transition = 'all 0.2s ease';
            });

            bubble.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Enhanced form submission feedback
        document.addEventListener('DOMContentLoaded', function() {
            const updateForm = document.querySelector('form[action*="update"]');
            if (updateForm) {
                updateForm.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '⏳ Đang lưu...';
                        submitBtn.style.background = 'linear-gradient(135deg, #94a3b8, #64748b)';
                        submitBtn.disabled = true;
                    }
                });
            }
        });
    </script>
@endsection
