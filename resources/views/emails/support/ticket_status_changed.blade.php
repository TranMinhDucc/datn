@component('mail::message')
# {{ $ticket->subject }}

Trạng thái phiếu **#{{ $ticket->id }}** đã đổi:  
**{{ $oldLabel }} → {{ $newLabel }}**.

@component('mail::button', ['url' => route('admin.support.tickets.show', $ticket)])
Xem phiếu
@endcomponent
@endcomponent
