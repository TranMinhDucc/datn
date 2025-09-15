@extends('layouts.admin') {{-- dùng layout admin của bạn nếu có --}}
@section('content')
@php
  $statusMap = [
    'open'             => ['#FFF7E6','#B26A00','Đang mở'],
    'waiting_customer' => ['#FFF7E6','#B26A00','Chờ khách'],
    'waiting_admin'    => ['#E8F4FF','#1565C0','Chờ shop'],
    'resolved'         => ['#E9F7EF','#2E7D32','Đã xử lý'],
    'closed'           => ['#ECEFF1','#455A64','Đã đóng'],
  ];
@endphp
<div style="max-width:1200px;margin:16px auto;padding:0 14px;">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
    <h2 style="margin:0;">Quản lý phiếu hỗ trợ</h2>
  </div>

  <form method="get" style="display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr 1fr;gap:8px;margin-bottom:12px;">
    <input name="q" value="{{ $q }}" placeholder="Tìm #ID / tiêu đề / mã đơn"
           style="padding:10px;border:1px solid #eee;border-radius:10px;">
    <select name="status" style="padding:10px;border:1px solid #eee;border-radius:10px;">
      <option value="">Trạng thái</option>
      @foreach(['open','waiting_customer','waiting_admin','resolved','closed'] as $st)
        <option value="{{ $st }}" {{ $status===$st?'selected':'' }}>{{ $st }}</option>
      @endforeach
    </select>
    <select name="priority" style="padding:10px;border:1px solid #eee;border-radius:10px;">
      <option value="">Ưu tiên</option>
      @foreach(['urgent'=>'Khẩn cấp','high'=>'Cao','normal'=>'B.thường'] as $k=>$v)
        <option value="{{ $k }}" {{ $prio===$k?'selected':'' }}>{{ $v }}</option>
      @endforeach
    </select>
    <select name="assigned_to" style="padding:10px;border:1px solid #eee;border-radius:10px;">
      <option value="">Gán cho</option>
      <option value="0" {{ $assigned==='0'?'selected':'' }}>— Không gán —</option>
      @foreach($agents as $a)
        <option value="{{ $a->id }}" {{ (string)$assigned===(string)$a->id?'selected':'' }}>{{ $a->name }}</option>
      @endforeach
    </select>
    <select name="sort" style="padding:10px;border:1px solid #eee;border-radius:10px;">
      <option value="latest"   {{ $sort==='latest'?'selected':'' }}>Mới cập nhật</option>
      <option value="oldest"   {{ $sort==='oldest'?'selected':'' }}>Cũ nhất</option>
      <option value="priority" {{ $sort==='priority'?'selected':'' }}>Ưu tiên cao</option>
    </select>
    <div style="grid-column:1 / -1;display:flex;gap:8px;justify-content:flex-end;">
      <button class="btn" style="padding:10px 14px;border-radius:10px;border:1px solid #eee;background:#fafafa;cursor:pointer;">Lọc</button>
      <a href="{{ route('admin.support.tickets.index') }}" style="padding:10px 14px;border-radius:10px;border:1px solid #eee;background:#fff;text-decoration:none;color:#333;">Xoá lọc</a>
    </div>
  </form>

  @forelse($tickets as $t)
    @php [$bg,$color,$label] = $statusMap[$t->status] ?? ['#eee','#555',$t->status]; @endphp
    <a href="{{ route('admin.support.tickets.show',$t->id) }}" style="text-decoration:none;color:inherit;">
      <div style="display:grid;grid-template-columns:1fr 180px 150px 160px 160px;gap:12px;align-items:center;
                  background:#fff;border:1px solid #f0f0f0;border-radius:14px;padding:12px 14px;margin-bottom:10px;
                  box-shadow:0 6px 16px rgba(0,0,0,.05);">
        <div>
          <div style="font-weight:800;">#{{ $t->id }} — {{ $t->subject }}</div>
          <div style="color:#666;margin-top:4px;">
            KH: {{ $t->user->fullname ?? '—' }} • Mã đơn: {{ $t->order_code ?: '—' }} • Nhóm: {{ $t->category }}
          </div>
        </div>
        <div style="text-align:center;">
          <span style="padding:6px 10px;border-radius:999px;background:{{ $bg }};color:{{ $color }};font-weight:800;">{{ $label }}</span>
        </div>
        <div style="text-align:center;">Ưu tiên: <b>{{ $t->priority }}</b></div>
        <div style="text-align:center;">Gán: <b>{{ optional($t->assignee)->name ?? ($t->assigned_to?'—':'(Không)') }}</b></div>
        <div style="text-align:right;color:#666;">Cập nhật: {{ optional($t->updated_at)->format('d/m/Y H:i') }}</div>
      </div>
    </a>
  @empty
    <div style="background:#fff;border:1px solid #f0f0f0;border-radius:14px;padding:18px;text-align:center;">
      Chưa có phiếu.
    </div>
  @endforelse

  <div style="display:flex;justify-content:center;gap:8px;margin-top:12px;">
    {{ $tickets->onEachSide(1)->links() }}
  </div>
</div>
@endsection
