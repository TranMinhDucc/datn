@extends('layouts.admin')
@section('title','Danh sách hoàn MoMo')

@section('content')
<div class="container-xxl">


  <div class="card shadow-sm">
    <div class="card-header">
      <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <h5 class="mb-0">Hoàn tiền MoMo</h5>
        <a href="{{ route('admin.manual_refund.create') }}" class="btn btn-primary">+ Tạo hoàn mới</a>
      </div>
    </div>

    <div class="card-body">
      <form class="row g-2 mb-3" method="GET">
        <div class="col-md-3">
          <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Tìm theo ID hoàn / mã đơn">
        </div>
        <div class="col-md-3">
          <select name="status" class="form-select">
            <option value="">-- Tất cả trạng thái --</option>
            @foreach(['pending'=>'pending','done'=>'done','failed'=>'failed','canceled'=>'canceled'] as $k=>$label)
              <option value="{{ $k }}" @selected($status===$k)>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <button class="btn btn-outline-secondary">Lọc</button>
          <a href="{{ route('admin.manual_refund.index') }}" class="btn btn-link">Xóa lọc</a>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Mã đơn</th>
              <th>Khách</th>
              <th>Số tiền</th>
              <th>Phương thức</th>
              <th>Trạng thái</th>
              <th>Ref</th>
              <th>Tạo lúc</th>
              <th>Hành động</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse($refunds as $rf)
              <tr>
                <td>{{ $rf->id }}</td>
                <td>
                  {{ $rf->order->order_code ?? $rf->order_id }}
                  
                </td>
                <td>{{ $rf->user->fullname ?? '—' }}</td>
                <td>{{ number_format($rf->amount,0,',','.') }}đ</td>
                <td>{{ strtoupper($rf->method) }}</td>
                <td>
                  @php $color = $rf->status==='done' ? 'success' : ($rf->status==='failed'?'danger':'secondary'); @endphp
                  <span class="badge text-bg-{{ $color }}">{{ $rf->status }}</span>
                </td>
                <td class="small">{{ $rf->bank_ref ?? '—' }}</td>
                <td class="small">{{ $rf->created_at }}</td>
                <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light btn-active-light-primary"
                                                data-bs-toggle="dropdown">
                                                <i class="fa-solid fa-ellipsis-h"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="{{ route('admin.manual_refund.show', $rf->id) }}"
                                                        class="dropdown-item">
                                                        <i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Chi tiết
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
              </tr>
            @empty
              <tr><td colspan="9" class="text-center text-muted py-4">Chưa có bản ghi</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if($refunds->hasPages())
      <div class="card-footer">{{ $refunds->links() }}</div>
    @endif
  </div>
</div>
@endsection
