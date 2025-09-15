<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;       // ← thay cho SupportTicketThread
use App\Models\SupportMessageAttachment;   // ← để lưu đính kèm
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupportTicketController extends Controller
{
    public function index(Request $r)
    {
        $q       = trim($r->q ?? '');
        $status  = $r->status ?? '';
        $prio    = $r->priority ?? '';
        $assigned = $r->assigned_to ?? '';
        $sort    = $r->sort ?? 'latest';

        $tickets = SupportTicket::with(['user:id,fullname,email'])
            ->when($q, function ($qr) use ($q) {
                $qr->where(function ($x) use ($q) {
                    $x->where('subject', 'like', "%$q%")
                        ->orWhere('order_code', 'like', "%$q%")
                        ->orWhere('id', $q);
                });
            })
            ->when($status, fn($qr) => $qr->where('status', $status))
            ->when($prio,   fn($qr) => $qr->where('priority', $prio))
            ->when($assigned !== '', fn($qr) => $qr->where('assigned_to', $assigned ? $assigned : null));

        // sort
        if ($sort === 'oldest')         $tickets->oldest('updated_at');
        elseif ($sort === 'priority')   $tickets->orderByRaw("FIELD(priority,'urgent','high','normal') ASC")->latest('updated_at');
        else                          $tickets->latest('updated_at');

        $tickets = $tickets->paginate(12)->withQueryString();
        $agents = \App\Models\User::whereIn('role', ['admin', 'support'])   // hoặc ['admin'] tùy bạn
            ->get(['id', 'fullname as name']);

        return view('admin.support.index', compact('tickets', 'q', 'status', 'prio', 'assigned', 'sort', 'agents'));
    }

    public function show(SupportTicket $ticket)
    {
        // nạp user + các message (mới nhất trước) kèm attachments
        $ticket->load([
            'user:id,fullname,email',
            'messages' => fn($q) => $q->oldest()->with(['user:id,fullname,email', 'attachments']),
        ]);

        // nếu bạn chỉ có role 'admin' thì giữ 1 giá trị, nếu có cả 'support' thì thêm vào mảng
        $agents = \App\Models\User::whereIn('role', ['admin'])   // hoặc ['admin','support']
            ->get(['id', 'fullname as name']);                   // alias -> name để view dùng chung

        return view('admin.support.show', compact('ticket', 'agents'));
    }



    public function update(Request $r, SupportTicket $ticket)
    {
        $data = $r->validate([
            'status'      => 'nullable|in:open,waiting_customer,waiting_staff,resolved,closed',
            'priority'    => 'nullable|in:low,normal,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        // nếu chọn “Không gán”
        if ($r->filled('assigned_to') && $r->assigned_to === '0') {
            $data['assigned_to'] = null;
        }

        $ticket->fill($data)->save();

        // log một note vào thread (hệ thống)
        $sm = \App\Models\SupportTicket::statusMap();
        $pm = \App\Models\SupportTicket::priorityMap();

        $noteParts = [];
        if ($r->filled('status'))   $noteParts[] = "Cập nhật trạng thái: " . ($sm[$r->status] ?? $r->status);
        if ($r->filled('priority')) $noteParts[] = "Cập nhật ưu tiên: " . ($pm[$r->priority] ?? $r->priority);
        if ($r->has('assigned_to')) $noteParts[] = 'Cập nhật người xử lý';

        if ($noteParts) {
            SupportTicketMessage::create([
                'support_ticket_id' => $ticket->id,
                'user_id'           => Auth::id(),   // phải truyền
                'is_staff'          => true,         // phân biệt admin / client
                'body'              => '• ' . implode(' | ', $noteParts),
            ]);
        }

        return back()->with('success', 'Đã cập nhật phiếu.');
    }

    public function reply(Request $r, SupportTicket $ticket)
    {
        $data = $r->validate([
            'body'          => 'required|string|min:2',
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp,mp4,pdf',
        ]);

        // admin reply → chuyển sang waiting_customer nếu chưa đóng
        if (!in_array($ticket->status, ['resolved', 'closed'])) {
            $ticket->status = 'waiting_customer';
            $ticket->save();
        }

        // ✅ Ghi message đúng schema
        $msg = SupportTicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id'           => auth()->id(),
            'is_staff'          => true,
            'body'              => $data['body'],
        ]);

        // ✅ Lưu file đính kèm
        if ($r->hasFile('attachments')) {
            foreach ($r->file('attachments') as $file) {
                $path = $file->store('support_attachments', 'public');
                SupportMessageAttachment::create([
                    'support_ticket_message_id' => $msg->id,
                    'path'          => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime'          => $file->getClientMimeType(),
                    'size'          => $file->getSize(),
                ]);
            }
        }

        return back()->with('success', 'Đã gửi trả lời.');
    }
}
