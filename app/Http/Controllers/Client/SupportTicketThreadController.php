<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\SupportMessageAttachment;


class SupportTicketThreadController extends Controller
{
    public function show(SupportTicket $ticket)
    {
        abort_if($ticket->user_id !== Auth::id(), 403);
        $ticket->load(['messages.user', 'messages.attachments']);
        return view('client.support.thread', compact('ticket'));
    }

    public function reply(Request $r, SupportTicket $ticket)
    {
        abort_if($ticket->user_id !== Auth::id(), 403);

        $data = $r->validate([
            'body' => 'required|string|min:2',
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp,mp4,pdf',
        ]);
        if ($ticket->status === 'closed') {
            return back()->withErrors('Phiếu đã đóng, không thể gửi thêm tin nhắn.');
        }

        $msg = SupportTicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id'  => Auth::id(),
            'is_staff' => false,
            'body'     => $data['body'],
        ]);

        if ($r->hasFile('attachments')) {
            foreach ($r->file('attachments') as $file) {
                $path = $file->store('support_messages', 'public');
                SupportMessageAttachment::create([
                    'support_ticket_message_id' => $msg->id,
                    'path'          => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime'          => $file->getClientMimeType(),
                    'size'          => $file->getSize(),
                ]);
            }
        }

        // client trả lời → ticket quay lại in_progress
        if (in_array($ticket->status, ['waiting_customer', 'open'])) {
            $ticket->update(['status' => 'in_progress']);
        }

        return back();
    }
}
