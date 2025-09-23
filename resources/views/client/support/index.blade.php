@extends('layouts.client')
@section('content')
@php
  // ====== Mappings ======
  $statusMap = [
    'open'              => ['bg' => '#FFF2E2', 'fg' => '#9A5A00', 'label' => 'Đang mở',          'icon' => '🟡'],
    'waiting_customer'  => ['bg' => '#FFF2E2', 'fg' => '#9A5A00', 'label' => 'Chờ bạn phản hồi',  'icon' => '🕒'],
    // hỗ trợ cả 2 key để tránh lệch DB
    'waiting_staff'     => ['bg' => '#E7F1FF', 'fg' => '#0E5CAD', 'label' => 'Chờ shop',          'icon' => '📩'],
    'waiting_admin'     => ['bg' => '#E7F1FF', 'fg' => '#0E5CAD', 'label' => 'Chờ shop',          'icon' => '📩'],
    'resolved'          => ['bg' => '#E9F7EF', 'fg' => '#1B7A38', 'label' => 'Đã xử lý',         'icon' => '✅'],
    'closed'            => ['bg' => '#EEF2F5', 'fg' => '#3B4A58', 'label' => 'Đã đóng',          'icon' => '🔒'],
  ];

  $priorityMap = [
    'urgent' => ['bg' => '#FCE8E6', 'fg' => '#C62828', 'label' => 'Khẩn cấp'],
    'high'   => ['bg' => '#FFF2E2', 'fg' => '#9A5A00', 'label' => 'Cao'],
    'normal' => ['bg' => '#EDEFF3', 'fg' => '#3B4A58', 'label' => 'Bình thường'],
  ];

  $pills = [
    ['key' => '',                 'label' => 'Tất cả'],
    ['key' => 'open',             'label' => 'Đang mở'],
    ['key' => 'waiting_customer', 'label' => 'Chờ bạn'],
    ['key' => 'waiting_staff',    'label' => 'Chờ shop'],
    ['key' => 'resolved',         'label' => 'Đã xử lý'],
    ['key' => 'closed',           'label' => 'Đã đóng'],
  ];
@endphp

<style>
  :root{
    --brand:#c69c6d; --ink:#1f2937; --muted:#6b7280; --card:#ffffff; --line:#eef2f7;
    --soft:0 10px 26px rgba(0,0,0,.06); --hover:0 16px 34px rgba(0,0,0,.10);
  }
  .page{max-width:1120px;margin:24px auto;padding:0 16px;}
  .head{
    display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;
  }
  .title{margin:0;font-weight:900;letter-spacing:.2px}
  .btn{
    display:inline-flex;align-items:center;gap:8px;cursor:pointer;
    padding:10px 14px;border-radius:12px;border:1px solid transparent;font-weight:800;
    text-decoration:none;transition:all .18s ease;
  }
  .btn-primary{background:var(--brand);color:#fff;box-shadow:0 8px 18px rgba(198,156,109,.25)}
  .btn-primary:hover{transform:translateY(-1px);filter:saturate(1.05)}
  .btn-ghost{background:#fff;border-color:var(--line);color:var(--ink)}
  .toolbar{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:12px}
  .pill{
    text-decoration:none;padding:8px 12px;border-radius:999px;border:1px solid var(--line);
    background:#fff;color:var(--ink);font-weight:700;box-shadow:0 4px 10px rgba(0,0,0,.03);
    transition:all .16s ease;
  }
  .pill:hover{transform:translateY(-1px)}
  .pill.active{border-color:var(--brand);background:#fbf6ef;color:#7b5737}

  .searchbar{display:flex;gap:10px;align-items:center;margin-bottom:16px}
  .input{
    flex:1;padding:12px 14px;border:1px solid var(--line);border-radius:12px;background:#fff;
    box-shadow:0 4px 10px rgba(0,0,0,.04);outline:none
  }
  .select{padding:12px;border:1px solid var(--line);border-radius:12px;background:#fff}

  .card{
    display:grid;grid-template-columns:1fr 150px 160px 160px;gap:14px;align-items:center;
    padding:16px;border:1px solid var(--line);border-radius:16px;background:var(--card);
    box-shadow:var(--soft);transition:box-shadow .2s ease, transform .2s ease;margin-bottom:12px;
  }
  .card:hover{box-shadow:var(--hover);transform:translateY(-2px)}
  .card a{color:inherit;text-decoration:none}
  .chips{display:flex;gap:8px;flex-wrap:wrap;margin-top:6px}
  .chip{
    display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;font-weight:700
  }
  .status{
    padding:6px 12px;border-radius:999px;font-weight:900;display:inline-flex;gap:8px;align-items:center
  }
  .meta{text-align:center}
  .meta .sub{color:var(--muted);font-size:.92rem}
  .right{text-align:right}
  .empty{
    border:1px solid var(--line);border-radius:16px;background:#fff;padding:28px;text-align:center;box-shadow:var(--soft)
  }
  @media (max-width:980px){ .card{grid-template-columns:1fr;align-items:flex-start} .right,.meta{text-align:left}}



  /* ==== EQUAL HEIGHT + CỘT CỐ ĐỊNH ==== */
.card{
  height: 124px !important;            /* đổi 116/128/136 tuỳ mắt */
  overflow: hidden;
  align-items: stretch !important;
  grid-template-columns: minmax(0,1fr) 190px 160px 200px !important;
  /*          (nội dung)        (trạng thái) (tạo lúc) (cập nhật)  */
}
.card > div{
  min-width: 0;                         /* NGĂN cột 1 bị nở */
  display: flex;
  align-items: center;                   /* giữa theo trục dọc */
  height: 100%;
  box-sizing: border-box;
}

/* ==== KHÔNG CHO XUỐNG DÒNG Ở NHỮNG CỘT DỄ LỆCH ==== */
.status{
  width: 180px;                          /* đủ chứa 'Chờ bạn phản hồi' */
  white-space: nowrap;
  justify-content: center;
  text-align: center;
}
.meta > div, .right > div, .right .sub{
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  width: 100%;
}

/* ==== CẮT GỌN TIÊU ĐỀ & CHIPS VỀ 1 HÀNG ==== */
.chips{ flex-wrap: nowrap; overflow: hidden; }
.chip{ white-space: nowrap; max-width: 100%; }
.card .title-row, .card .subject, .card .chips{ min-width: 0; }


</style>

<div class="page">

  <div class="head">
    <h2 class="title">Phiếu Hỗ Trợ Của Tôi</h2>
    <a href="{{ route('support.tickets.create') }}" class="btn btn-primary">＋ Tạo phiếu</a>
  </div>

  {{-- filter pills --}}
  <div class="toolbar">
    @foreach($pills as $p)
      @php
        $qs = array_filter(array_merge(request()->only(['q','sort']), ['status' => $p['key'] ?: null]));
        $url = route('support.tickets.index', $qs);
        $active = ($p['key'] === ($status ?? '')) ? 'active' : '';
      @endphp
      <a class="pill {{ $active }}" href="{{ $url }}">{{ $p['label'] }}</a>
    @endforeach
  </div>

  {{-- search + sort --}}
  <form class="searchbar" method="get" action="{{ route('support.tickets.index') }}">
    <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Tìm tiêu đề / mã đơn / #id">
    <select class="select" name="status" style="display:none">
      <option value="{{ $status }}" selected></option>
    </select>
    <select class="select" name="sort">
      <option value="latest"   {{ $sort==='latest'?'selected':'' }}>Mới cập nhật</option>
      <option value="oldest"   {{ $sort==='oldest'?'selected':'' }}>Cũ nhất</option>
      <option value="priority" {{ $sort==='priority'?'selected':'' }}>Ưu tiên cao</option>
    </select>
    <button class="btn btn-ghost" type="submit">Lọc</button>
  </form>

  @if($tickets->count())
    @foreach($tickets as $t)
      @php
        $s = $statusMap[$t->status] ?? ['bg' => '#EEE','fg' => '#333','label' => ucfirst($t->status),'icon' => '•'];
        $p = $priorityMap[$t->priority] ?? ['bg' => '#EEE','fg' => '#333','label' => $t->priority];
      @endphp

      <a href="{{ route('support.tickets.thread.show', $t->id) }}">
        <div class="card">
          {{-- left block --}}
          <div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
              <span style="font-weight:900">#{{ $t->id }}</span>
              <span style="font-weight:800">{{ $t->subject }}</span>
            </div>
            <div class="chips">
              <span class="chip" style="background:{{ $p['bg'] }};color:{{ $p['fg'] }}">Ưu tiên: {{ $p['label'] }}</span>
              <span class="chip" style="background:#EEF3F7;color:#3B4A58">Nhóm: {{ $t->category }}</span>
              <span class="chip" style="background:#F5F7FA;color:#475569">Mã đơn: <b style="margin-left:6px">{{ $t->order_code ?: '—' }}</b></span>
            </div>
          </div>

          {{-- status --}}
          <div class="meta">
            <span class="status" style="background:{{ $s['bg'] }};color:{{ $s['fg'] }}">{{ $s['icon'] }} {{ $s['label'] }}</span>
          </div>

          {{-- created --}}
          <div class="meta">
            <div style="font-weight:800">{{ optional($t->created_at)->format('d/m/Y') }}</div>
            <div class="sub">Tạo lúc {{ optional($t->created_at)->format('H:i') }}</div>
          </div>

          {{-- updated --}}
          <div class="right">
            <div style="font-weight:800">Cập nhật: {{ optional($t->updated_at)->format('d/m/Y H:i') }}</div>
            <div class="sub">Nhấn để mở hội thoại »</div>
          </div>
        </div>
      </a>
    @endforeach

    {{-- pagination --}}
    <div style="display:flex;justify-content:center;gap:8px;margin-top:16px;flex-wrap:wrap">
      @if ($tickets->onFirstPage() === false)
        <a class="pill" href="{{ $tickets->previousPageUrl() }}">« Trước</a>
      @endif

      @foreach ($tickets->getUrlRange(max(1,$tickets->currentPage()-2), min($tickets->lastPage(),$tickets->currentPage()+2)) as $p => $url)
        <a class="pill {{ $p==$tickets->currentPage()? 'active':'' }}" href="{{ $url }}">{{ $p }}</a>
      @endforeach

      @if ($tickets->hasMorePages())
        <a class="pill" href="{{ $tickets->nextPageUrl() }}">Sau »</a>
      @endif
    </div>
  @else
    <div class="empty">
      <div style="font-size:56px;line-height:1">📭</div>
      <h3 style="margin:8px 0 4px">Bạn chưa có phiếu hỗ trợ nào</h3>
      <p style="margin:0;color:var(--muted)">Hãy tạo phiếu đầu tiên để chúng tôi hỗ trợ bạn nhanh hơn.</p>
      <a href="{{ route('support.tickets.create') }}" class="btn btn-primary" style="margin-top:12px">＋ Tạo phiếu</a>
    </div>
  @endif
</div>
@endsection
