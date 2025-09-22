@component('mail::message')
# {{ $ticket->subject }}

Bạn có **phản hồi mới** cho phiếu **#{{ $ticket->id }}**.

@component('mail::panel')
{!! nl2br(e($message->body)) !!}
@endcomponent

@component('mail::button', ['url' => route('admin.support.tickets.show', $ticket)])
Xem chi tiết phiếu
@endcomponent

Cảm ơn bạn,<br>
{{ config('app.name') }}
@endcomponent
