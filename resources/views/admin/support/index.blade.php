@extends('layouts.admin')

@section('content')
@php
$statusMap = [
'open' => ['#FFF3E0', '#FF8C00', 'Đang mở', '🔓'],
'waiting_customer' => ['#FFF7E6', '#B26A00', 'Chờ khách', '⏳'],
'waiting_admin' => ['#E8F4FF', '#1565C0', 'Chờ shop', '🔄'],
'resolved' => ['#E9F7EF', '#2E7D32', 'Đã xử lý', '✅'],
'closed' => ['#ECEFF1', '#455A64', 'Đã đóng', '🔒'],
];

$priorityColors = [
'urgent' => ['#FFEBEE', '#D32F2F', '🚨'],
'high' => ['#FFF3E0', '#F57C00', '⚡'],
'normal' => ['#F1F8E9', '#388E3C', '📝'],
];
@endphp

<style>
    .support-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .header-section {
        background: white;
        padding: 24px;
        border-radius: 20px;
        margin-bottom: 24px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .filter-card {
        background: white;
        padding: 24px;
        border-radius: 20px;
        margin-bottom: 24px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }

    .form-input,
    .form-select {
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: white;
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        transform: translateY(-1px);
    }

    .form-input::placeholder {
        color: #a0aec0;
    }

    .filter-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        grid-column: 1 / -1;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 14px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: #f7fafc;
        color: #4a5568;
        border: 2px solid #e2e8f0;
    }

    .btn-secondary:hover {
        background: #edf2f7;
        transform: translateY(-1px);
    }

    .ticket-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
        position: relative;
        overflow: hidden;
    }

    .ticket-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: inherit;
    }

    .ticket-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .ticket-grid {
        display: grid;
        grid-template-columns: 1fr 180px 140px 160px 180px;
        gap: 20px;
        align-items: center;
    }

    .ticket-main {
        min-width: 0;
    }

    .ticket-id-subject {
        font-weight: 700;
        font-size: 16px;
        color: #2d3748;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ticket-id {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .ticket-meta {
        color: #718096;
        font-size: 13px;
        line-height: 1.4;
    }

    .ticket-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-right: 12px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .priority-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
        font-size: 13px;
    }

    .assigned-info {
        text-align: center;
        font-size: 13px;
    }

    .assigned-name {
        font-weight: 600;
        color: #4a5568;
    }

    .update-time {
        text-align: right;
        color: #718096;
        font-size: 12px;
    }

    .empty-state {
        background: white;
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .empty-text {
        color: #718096;
        font-size: 16px;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 32px;
    }

    @media (max-width: 1200px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }

        .ticket-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .ticket-grid>div {
            text-align: left !important;
        }
    }

    
</style>
@php
$categoryMap = [
  'order'   => 'order',
  'product' => 'product',
  'payment' => 'payment',
  'account' => 'account',
  'shipping'=> 'shipping',
  'refund'  => 'refund',
  'other'   => 'other',
];

$vnStatus = [
  'open'             => 'Đang mở',
  'waiting_customer' => 'Chờ khách',
  'waiting_staff'    => 'Chờ shop',
  'resolved'         => 'Đã xử lý',
  'closed'           => 'Đã đóng',
];
@endphp

<style>

    
:root{
  --bg:#f6f8fb;
  --card:#fff;
  --ink:#1f2937;
  --muted:#6b7280;
  --ring:rgba(103,119,239,.25);
  --chip-bg:#f3f4f6;
  --chip-ink:#374151;
  --radius:18px;
  --shadow:0 6px 18px rgba(16,24,40,.08);
  --shadow-hover:0 10px 28px rgba(16,24,40,.12);
}

/* nền tổng thể */
.support-container{ background:radial-gradient(1000px 600px at 90% -10%, #eef2ff 0%, transparent 60%), var(--bg); }

/* thẻ phiếu */
.ticket-card{
  background:var(--card);
  border-radius:var(--radius);
  padding:18px 20px;
  margin-bottom:14px;
  box-shadow:var(--shadow);
  border:1px solid #eef2f7;
  transition:.18s ease;
}
.ticket-card:hover{ transform:translateY(-2px); box-shadow:var(--shadow-hover); }

/* thanh nhấn sắc ở mép trái */
.ticket-card::before{
  content:"";
  position:absolute; inset:0 auto 0 0; width:4px; border-radius:16px 0 0 16px;
  background:linear-gradient(180deg,#6d83f3,#a678e3);
}

/* layout item */
.ticket-row{ display:grid; grid-template-columns: 1fr auto auto 120px; gap:18px; align-items:center; }
.ticket-main{ min-width:0; }
.ticket-title{ display:flex; align-items:center; gap:8px; margin:0 0 6px; font-weight:700; color:var(--ink); font-size:15px; }
.ticket-title .id{ background:#6366f1; color:#fff; padding:2px 8px; border-radius:9px; font-size:12px; font-weight:700; }

.ticket-meta{ display:flex; flex-wrap:wrap; gap:10px 14px; color:var(--muted); font-size:13px; }
.meta{ display:inline-flex; align-items:center; gap:6px; white-space:nowrap; }

.chip{
  display:inline-flex; align-items:center; gap:6px;
  padding:6px 10px; border-radius:999px; font-weight:600; font-size:12px;
  border:1px solid rgba(0,0,0,.06); background:var(--chip-bg); color:var(--chip-ink);
}

/* status / priority chips */
.chip-status{ text-transform:uppercase; letter-spacing:.3px; }
.chip-prio{ font-size:12px; }

.badge--open{ background:#eef2ff; color:#4f46e5; }
.badge--waiting_customer{ background:#fff7ed; color:#b45309; }
.badge--waiting_staff{ background:#e0f2fe; color:#0369a1; }
.badge--resolved{ background:#ecfdf5; color:#059669; }
.badge--closed{ background:#f1f5f9; color:#334155; }

.prio--urgent{ background:#fee2e2; color:#b91c1c; }
.prio--high{ background:#fff1e6; color:#c2410c; }
.prio--normal{ background:#ecfdf5; color:#047857; }

/* cột thời gian */
.time{ text-align:right; color:var(--muted); font-size:12px; }
.time strong{ display:block; color:#475569; }

/* responsive */
@media (max-width: 1200px){
  .ticket-row{ grid-template-columns: 1fr auto; }
  .ticket-status, .ticket-prio{ justify-self:start; }
  .time{ display:none; }
}
</style>


<div class="support-container">
    <!-- Header Section -->
    <div class="header-section d-flex align-items-center justify-content-between gap-3">
        <h1 class="page-title m-0">🎫 Quản lý phiếu hỗ trợ</h1>

        <a href="{{ route('admin.support.tickets.create') }}"
            class="btn btn-primary ms-auto">
            + Tạo phiếu hỗ trợ
        </a>
    </div>


    <!-- Filter Card -->
    <div class="filter-card">
        <form method="get">
            <div class="filter-grid">
                <input name="q" value="{{ $q }}"
                    placeholder="🔍 Tìm kiếm #ID, tiêu đề hoặc mã đơn hàng..." class="form-input">

                <select name="status" class="form-select">
                    <option value="">📊 Tất cả trạng thái</option>
                    @foreach (['open', 'waiting_customer', 'waiting_admin', 'resolved', 'closed'] as $st)
                    <option value="{{ $st }}" {{ $status === $st ? 'selected' : '' }}>
                        {{ $statusMap[$st][2] ?? $st }}
                    </option>
                    @endforeach
                </select>

                <select name="priority" class="form-select">
                    <option value="">⚡ Độ ưu tiên</option>
                    @foreach (['urgent' => 'Khẩn cấp', 'high' => 'Cao', 'normal' => 'Bình thường'] as $k => $v)
                    <option value="{{ $k }}" {{ $prio === $k ? 'selected' : '' }}>{{ $v }}
                    </option>
                    @endforeach
                </select>

                

                <select name="sort" class="form-select">
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>🕒 Mới cập nhật</option>
                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>📅 Cũ nhất</option>
                    <option value="priority" {{ $sort === 'priority' ? 'selected' : '' }}>⭐ Ưu tiên cao</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    🔍 Tìm kiếm
                </button>
                <a href="{{ route('admin.support.tickets.index') }}" class="btn btn-secondary">
                    🔄 Xóa bộ lọc
                </a>
            </div>
        </form>
    </div>

    <!-- Tickets List -->
    @forelse($tickets as $t)
    @php
    [$bg, $color, $label, $icon] = $statusMap[$t->status] ?? ['#f7fafc', '#4a5568', $t->status, '❓'];
    $priorityData = $priorityColors[$t->priority] ?? ['#f7fafc', '#4a5568', '📝'];
    @endphp

    <a href="{{ route('admin.support.tickets.show', $t->id) }}" class="ticket-card">
  <div class="ticket-row">
    <!-- cột trái: tiêu đề + meta -->
    <div class="ticket-main">
      <h3 class="ticket-title">
        <span class="id">#{{ $t->id }}</span>
        <span class="line-clamp-1" style="display:inline-block;max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
          {{ $t->subject }}
        </span>
      </h3>
      <div class="ticket-meta">
        <span class="meta">👤 <strong>{{ $t->user->fullname ?? 'Không xác định' }}</strong></span>
        <span class="meta">📦 {{ $t->order_code ?: 'Không có' }}</span>
        <span class="meta">🏷️ {{ $categoryMap[$t->category] ?? $t->category }}</span>
      </div>
    </div>

    <!-- cột 2: trạng thái -->
    <div class="ticket-status">
      @php $st = $t->status; @endphp
      <span class="chip chip-status badge--{{ $st }}">
        @switch($st)
          @case('open')              🔓 @break
          @case('waiting_customer')  ⏳ @break
          @case('waiting_staff')     🔄 @break
          @case('resolved')          ✅ @break
          @case('closed')            🔒 @break
          @default ❓
        @endswitch
        {{ $vnStatus[$st] ?? strtoupper($st) }}
      </span>
    </div>

    <!-- cột 3: ưu tiên -->
    <div class="ticket-prio">
      @php $p = $t->priority ?? 'normal'; @endphp
      <span class="chip chip-prio prio--{{ $p }}">
        @if($p==='urgent') 🚨 @elseif($p==='high') ⚡ @else 📝 @endif
        {{ ucfirst($p) }}
      </span>
    </div>

    <!-- cột 4: thời gian -->
    <div class="time">
      <strong>{{ optional($t->updated_at)->format('d/m/Y') }}</strong>
      <span>{{ optional($t->updated_at)->format('H:i') }}</span>
    </div>
  </div>
</a>

    @empty
    <div class="empty-state">
        <div class="empty-icon">📭</div>
        <div class="empty-text">Không có phiếu hỗ trợ nào được tìm thấy</div>
    </div>
    @endforelse

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $tickets->onEachSide(1)->links() }}
    </div>
</div>

<script>
    // Thêm hiệu ứng loading khi submit form
    document.querySelector('form').addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '⏳ Đang tìm kiếm...';
        submitBtn.disabled = true;
    });

    // Auto-focus vào search input khi load trang
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="q"]');
        if (searchInput && !searchInput.value) {
            searchInput.focus();
        }
    });
</script>
@endsection