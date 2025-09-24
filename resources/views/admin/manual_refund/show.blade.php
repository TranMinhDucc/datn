@extends('layouts.admin')
@section('title','Hoàn #'.$refund->id)

@section('content')

@push('styles')
<style>
  /* Phạm vi riêng cho trang này */
  .refund-page .refund-title{font-size:1.65rem;margin-bottom:.1rem}
  .refund-page .status-badge{font-size:.9rem}
  .refund-page .text-label{font-size:.95rem;color:#6c757d}
  .refund-page .refund-amount{font-size:2.25rem;line-height:1.25}
  .refund-page .refund-note{line-height:1.6}
  /* nới padding card */
  .refund-page .card-header{padding:.95rem 1.25rem}
  .refund-page .card-body{padding:1.5rem}
  /* dt/dd cho thoáng */
  .refund-page .refund-details dt{margin-bottom:.4rem}
  .refund-page .refund-details dd{margin-bottom:.9rem}
</style>
@endpush

<div class="container-xxl refund-page">
  {{-- Header --}}
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
      <div class="rounded-circle bg-light p-3">
        <i class="bi bi-arrow-counterclockwise fs-3 text-primary"></i>
      </div>
      <div>
        <h3 class="refund-title">Hoàn tiền #{{ $refund->id }}</h3>
        <div class="text-muted small">Tạo lúc {{ $refund->created_at }} • bởi {{ $refund->creator->name ?? ('#'.$refund->created_by) }}</div>
      </div>
    </div>
    @php $color = $refund->status==='done' ? 'success' : ($refund->status==='failed' ? 'danger' : 'secondary'); @endphp
    <div class="d-flex align-items-center gap-2">
      <span class="badge text-bg-{{ $color }} px-3 py-2 text-uppercase status-badge">{{ $refund->status }}</span>
      <a href="{{ route('admin.manual_refund.index') }}" class="btn btn-light">Danh sách</a>
      <a href="{{ route('admin.manual_refund.create') }}" class="btn btn-primary">+ Tạo hoàn mới</a>
    </div>
  </div>

  <div class="row g-4">
    {{-- Left: main info --}}
    <div class="col-lg-8">
      {{-- Amount highlight --}}
      <div class="card border-0 shadow-sm mb-2">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
          <div class="pe-lg-4">
            <div class="text-label">Số tiền hoàn</div>
            <div class="refund-amount fw-bold mt-1">
              {{ number_format($refund->amount,0,',','.') }} <span class="fs-6 fw-semibold">{{ $refund->currency }}</span>
            </div>
            <div class="text-label mt-2">Phương thức: <span class="fw-semibold">{{ strtoupper($refund->method) }}</span></div>
          </div>
          <div class="text-end mt-3 mt-lg-0">
            <div class="text-label">Bank Ref</div>
            <div class="d-flex align-items-center gap-2 justify-content-end mt-1">
              <code class="bg-light px-2 py-1 rounded small">{{ $refund->bank_ref ?? '—' }}</code>
              @if($refund->bank_ref)
                <button class="btn btn-sm btn-outline-secondary" data-copy="{{ $refund->bank_ref }}">Copy</button>
              @endif
            </div>
            <div class="text-label mt-3">
              Chuyển lúc: <span class="fw-semibold">{{ $refund->transferred_at ?? '—' }}</span>
            </div>
          </div>
        </div>
      </div>

      {{-- Two columns details --}}
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
          <strong>Chi tiết giao dịch</strong>
        </div>
        <div class="card-body">
          <div class="row g-3 refund-details">
            <div class="col-md-6">
              <div class="text-label">Mã đơn</div>
              <div class="fw-semibold mt-1">{{ $refund->order->order_code ?? $refund->order_id }}</div>
            </div>
            <div class="col-md-6">
              <div class="text-label">Mã GD MoMo</div>
              <div class="d-flex align-items-center gap-2 mt-1">
                <span class="fw-semibold">{{ $refund->order->momo_trans_id ?? '—' }}</span>
                @if(!empty($refund->order->momo_trans_id))
                  <button class="btn btn-sm btn-outline-secondary" data-copy="{{ $refund->order->momo_trans_id }}">Copy</button>
                @endif
              </div>
            </div>

            <div class="col-md-6">
              <div class="text-label">Tổng đơn</div>
              <div class="fw-semibold mt-1">{{ number_format($refund->order->total_amount ?? 0,0,',','.') }}đ</div>
            </div>
            <div class="col-md-6">
              <div class="text-label">Trạng thái đơn</div>
              <div class="fw-semibold mt-1 text-capitalize">{{ $refund->order->status ?? '—' }}</div>
            </div>

            <div class="col-12">
              <div class="text-label mb-2">Lý do/Ghi chú</div>
              <div class="border rounded p-3 bg-light-subtle refund-note">
                {{ $refund->note ?? '—' }}
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Timeline --}}
      <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white"><strong>Nhật ký</strong></div>
        <div class="card-body">
          <ul class="list-unstyled mb-0">
            <li class="d-flex align-items-start gap-3 mb-3">
              <span class="badge text-bg-secondary rounded-pill mt-1">Tạo</span>
              <div>
                <div class="fw-semibold">Tạo bản ghi hoàn</div>
                <div class="text-muted small">{{ $refund->created_at }} • {{ $refund->creator->name ?? ('#'.$refund->created_by) }}</div>
              </div>
            </li>
            @if($refund->transferred_at)
              <li class="d-flex align-items-start gap-3">
                <span class="badge text-bg-secondary rounded-pill mt-1">Chuyển</span>
                <div>
                  <div class="fw-semibold">Đã chuyển tiền</div>
                  <div class="text-muted small">{{ $refund->transferred_at }}</div>
                </div>
              </li>
            @endif
          </ul>
        </div>
      </div>
    </div>

    {{-- Right: customer & order --}}
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><strong>Khách hàng</strong></div>
        <div class="card-body">
          <div class="fw-semibold">{{ $refund->user->fullname ?? $refund->user->name ?? '—' }}</div>
          <div class="text-muted small mt-1">{{ $refund->user->email ?? '' }}</div>
        </div>
      </div>

      <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white"><strong>Đơn hàng</strong></div>
        <div class="card-body small">
          <div class="mb-2">Mã đơn: <span class="fw-semibold">{{ $refund->order->order_code ?? $refund->order_id }}</span></div>
          <div class="mb-2">Tổng tiền: <span class="fw-semibold">{{ number_format($refund->order->total_amount ?? 0,0,',','.') }}đ</span></div>
          <div class="mb-2">TT MoMo: <span class="fw-semibold">{{ $refund->order->momo_trans_id ?? '—' }}</span></div>
          <div class="mb-0">Trạng thái: <span class="fw-semibold text-capitalize">{{ $refund->order->status ?? '—' }}</span></div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // copy buttons
  document.querySelectorAll('[data-copy]').forEach(btn => {
    btn.addEventListener('click', () => {
      const text = btn.getAttribute('data-copy');
      navigator.clipboard.writeText(text).then(() => {
        btn.textContent = 'Copied';
        setTimeout(()=> btn.textContent='Copy', 1200);
      });
    });
  });
</script>
@endpush

@endsection
