{{-- resources/views/admin/refunds/index.blade.php --}}
@extends('layouts.admin')

@section('content')
    <h3 class="mb-3">Phiếu hoàn</h3>

    <form class="row g-2 mb-3">
        <div class="col-auto">
            <select name="status" class="form-select">
                <option value="">-- Tất cả --</option>
                @foreach (['pending' => 'Pending', 'done' => 'Done', 'failed' => 'Failed', 'canceled' => 'Canceled'] as $k => $v)
                    <option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto"><input type="date" name="from" value="{{ request('from') }}" class="form-control"></div>
        <div class="col-auto"><input type="date" name="to" value="{{ request('to') }}" class="form-control"></div>
        <div class="col-auto"><input type="text" name="search" value="{{ request('search') }}" class="form-control"
                placeholder="Order/User/BankRef"></div>
        <div class="col-auto"><button class="btn btn-primary">Lọc</button></div>
        <div class="col-auto"><a class="btn btn-outline-secondary"
                href="{{ route('admin.refunds.export', request()->query()) }}">Xuất CSV</a></div>
    </form>

    <table class="table table-sm align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Order</th>
                <th>Khách</th>
                <th>Số tiền</th>
                <th>Trạng thái</th>
                <th>BankRef</th>
                <th>Thời gian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($refunds as $r)
                <tr>
                    <td>#{{ $r->id }}</td>
                    <td>{{ $r->order->order_code ?? '—' }}</td>
                    <td>{{ $r->user->name ?? '—' }}<br><small>{{ $r->user->email ?? '' }}</small></td>
                    <td>{{ number_format($r->amount) }}</td>
                    <td><span
                            class="badge bg-{{ $r->status === 'done' ? 'success' : ($r->status === 'pending' ? 'warning text-dark' : 'secondary') }}">{{ $r->status }}</span>
                    </td>
                    <td>{{ $r->bank_ref ?? '—' }}</td>
                    <td>{{ $r->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $refunds->links() }}
@endsection
