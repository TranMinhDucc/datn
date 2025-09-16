@extends('layouts.client')
@section('content')
<div style="max-width:980px;margin:16px auto;padding:0 12px;">
  @if(session('success'))
    <div style="margin-bottom:12px;border:1px solid #d9f0d9;background:#f3fbf3;color:#2e7d32;padding:10px;border-radius:12px;">
      {{ session('success') }}
    </div>
  @endif

  <div style="background:#fff;border-radius:16px;padding:16px;box-shadow:0 10px 24px rgba(0,0,0,.06);">
    <h3 style="margin:0 0 6px;">Phiếu #{{ $ticket->id }} — {{ $ticket->subject }}</h3>
    <div style="color:#666;margin-bottom:10px;">Trạng thái: <b>{{ $ticket->status_label }}</b></div>

    <div id="thread" style="max-height:480px;overflow:auto;padding:10px;border:1px solid #eee;border-radius:12px;background:#fafafa;">
      @foreach($ticket->messages as $m)
        @php $mine = !$m->is_staff; @endphp
        <div style="display:flex;justify-content:{{ $mine?'flex-end':'flex-start' }};margin:6px 0;">
          <div style="max-width:70%;background:{{ $mine?'#e8f5e9':'#fff' }};border:1px solid #eee;border-radius:12px;padding:10px;">
            <div style="font-weight:700;font-size:.9rem;margin-bottom:4px;">
              {{ $mine ? ($m->user->name ?? 'Bạn') : 'CSKH' }}
              <span style="color:#888;font-weight:400;">— {{ $m->created_at->format('d/m H:i') }}</span>
            </div>
            <div style="white-space:pre-wrap;color:#333;">{{ $m->body }}</div>

            @if($m->attachments->count())
              <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:6px;">
                @foreach($m->attachments as $a)
                  <a href="{{ $a->url }}" target="_blank"
                     style="padding:6px 10px;border:1px solid #eee;border-radius:10px;background:#fff;text-decoration:none;font-size:.9rem;">
                    📎 {{ $a->original_name }}
                  </a>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>

    <form method="POST" action="{{ route('support.tickets.thread.reply',$ticket) }}" enctype="multipart/form-data" style="margin-top:10px;">
      @csrf
      <textarea name="body" required rows="3" placeholder="Nhập nội dung phản hồi..."
        style="width:100%;padding:10px;border:1px solid #eee;border-radius:10px;"></textarea>
      <div style="display:flex;justify-content:space-between;align-items:center;margin-top:6px;">
        <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.webp,.mp4,.pdf">
        <button type="submit" style="padding:8px 14px;border:0;border-radius:10px;background:#c69c6d;color:#fff;font-weight:800;cursor:pointer;">Gửi</button>
      </div>
    </form>
  </div>
</div>
<script>document.getElementById('thread').scrollTop = 999999;</script>
@endsection
