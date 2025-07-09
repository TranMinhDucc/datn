<div class="card card-flush py-4 flex-row-fluid">
    <div class="card-header">
        <div class="card-title">
            <h2>Địa chỉ nhận hàng</h2>
        </div>
    </div>
    <div class="card-body pt-0">
        {{ $order->address->title ?? '' }}<br />
        {{ $order->address->address ?? '' }}<br />
        {{ $order->address->city ?? '' }}, {{ $order->address->state ?? '' }}<br />
        {{ $order->address->country ?? '' }} - {{ $order->address->pincode ?? '' }}
    </div>
</div>
