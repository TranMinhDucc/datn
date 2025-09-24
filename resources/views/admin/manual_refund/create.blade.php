@extends('layouts.admin')
@section('title','Hoàn tiền MoMo')

@section('content')
<div class="container-xxl">
    <form method="POST" action="{{ route('admin.manual_refund.store') }}" id="refund-form">
        @csrf



        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tạo yêu cầu hoàn tiền MoMo</h5>
                <span class="badge text-bg-secondary">Trạng thái khi tạo: pending</span>
            </div>

            <div class="card-body">
                {{-- Chọn đơn bằng select2 ajax --}}
                <div class="mb-3">
                    <label class="form-label">Đơn hàng (đã thanh toán MoMo) <span class="text-danger">*</span></label>
                    <select id="order_select" class="form-select" required></select>
                    <input type="hidden" name="order_id" id="order_id" value="{{ old('order_id') }}">
                    <div class="form-text">Mở danh sách để xem tất cả, hoặc tìm theo mã đơn / ID.</div>
                </div>

                {{-- Thông tin ngắn của đơn --}}
                <div id="order-mini" class="row g-3 d-none">
                    <div class="col-md-4">
                        <label class="form-label">Mã đơn</label>
                        <input class="form-control" id="o_code" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Khách hàng</label>
                        <input class="form-control" id="o_customer" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Mã giao dịch MoMo</label>
                        <input class="form-control" id="o_momo" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tổng đơn</label>
                        <input class="form-control" id="o_total" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Đã thanh toán</label>
                        <input class="form-control" id="o_paid" readonly>
                    </div>
                    
                </div>

                <hr>

                {{-- Số tiền hoàn: auto-fill & readonly --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Số tiền hoàn (VND) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input
                                type="number"
                                name="amount"
                                id="amount"
                                class="form-control"
                                step="any"
                                inputmode="decimal"
                                min="0"
                                value="{{ old('amount') }}">
                            <span class="input-group-text">VND</span>
                        </div>

                        <div class="form-text">Tự động = số đã thanh toán – số đã hoàn trước đó.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ghi chú/Lý do</label>
                        <input type="text" name="note" class="form-control" placeholder="Khách hủy ngay sau thanh toán...">
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-12">
                        <label class="form-label">Mã tham chiếu ngân hàng (Bank ref)</label>
                        <input type="text" name="bank_ref" class="form-control"
                            maxlength="255" placeholder="VD: FT2430912345..."
                            value="{{ old('bank_ref') }}">
                        <div class="form-text">Nhập mã tham chiếu giao dịch bạn đã chuyển tay (nếu có).</div>
                    </div>

                </div>


                {{-- Ẩn các trường cố định --}}
                <input type="hidden" name="currency" value="VND">
                <input type="hidden" name="method" value="momo">
            </div>

            <div class="card-footer d-flex justify-content-between">
                <div class="small text-muted">Lưu ý: chỉ tạo hoàn khi đơn còn hợp lệ để refund MoMo.</div>
                <div class="d-flex gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-light">Hủy</a>
                    <button class="btn btn-danger" type="submit">Gửi yêu cầu hoàn tiền</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    (function() {
        const money = n => (n == null ? '' : Number(n).toLocaleString('vi-VN'));

        // Select2: hiện ngay + hỗ trợ cuộn để tải thêm
        $('#order_select').select2({
            placeholder: 'Tìm đơn MoMo...',
            ajax: {
                url: "{{ route('admin.ajax.orders.search') }}",
                dataType: 'json',
                delay: 200,
                data: function(params) {
                    return {
                        q: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results || [],
                        pagination: {
                            more: data.pagination && data.pagination.more
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 0,
            width: '100%'
        });


        // Khi chọn 1 đơn → lấy tóm tắt & set amount
        $('#order_select').on('select2:select', async function(e) {
            const selected = e.params.data;
            const orderId = selected.id;
            document.getElementById('order_id').value = orderId;

            if (selected.refundable === false) {
                alert('Đơn này không đủ điều kiện hoàn MoMo.');
                $('#order_select').val(null).trigger('change');
                return;
            }

            const res = await fetch("{{ route('admin.orders.ajax.lookup') }}?order_id=" + encodeURIComponent(orderId));
            let o;
            try {
                o = await res.json();
            } catch (_) {
                alert('Không lấy được thông tin đơn');
                return;
            }
            if (!o.ok) {
                alert('Không tìm thấy đơn hoặc dữ liệu không hợp lệ.');
                return;
            }

            document.getElementById('order-mini').classList.remove('d-none');
            document.getElementById('o_code').value = o.code || orderId;
            document.getElementById('o_customer').value = (o.customer?.name || '');
            document.getElementById('o_momo').value = o.momoTransId || '';
            document.getElementById('o_total').value = (o.orderTotal || 0).toLocaleString('vi-VN');
            document.getElementById('o_paid').value = (o.paidAmount || 0).toLocaleString('vi-VN');
            document.getElementById('o_left').value = (o.refundableLeft || 0).toLocaleString('vi-VN');
            document.getElementById('amount').value = o.refundableLeft || 0;

        });

        // Validate trước khi submit
        document.getElementById('refund-form').addEventListener('submit', function(e) {
            const orderId = document.getElementById('order_id').value;
            const amount = Number(document.getElementById('amount').value || 0);
            if (!orderId) {
                e.preventDefault();
                alert('Vui lòng chọn đơn MoMo.');
            }
            if (amount <= 0) {
                e.preventDefault();
                alert('Số tiền hoàn không hợp lệ.');
            }
        });
    })();
</script>
@endpush