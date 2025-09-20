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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupportTicketController extends Controller
{
    public function index(Request $r)
{
    $q      = trim($r->q ?? '');
    $status = $r->status ?? '';
    $prio   = $r->priority ?? '';
    $sort   = $r->sort ?? 'latest';

    $tickets = SupportTicket::with(['user:id,fullname,email'])
        ->when($q, function ($qr) use ($q) {
            $qr->where(function ($x) use ($q) {
                $x->where('subject', 'like', "%$q%")
                  ->orWhere('order_code', 'like', "%$q%");
                if (is_numeric($q)) {
                    $x->orWhere('id', (int)$q);
                }
            });
        })
        ->when($status, fn($qr) => $qr->where('status', $status))
        ->when($prio,   fn($qr) => $qr->where('priority', $prio));

    if ($sort === 'oldest') {
        $tickets->oldest('updated_at');
    } elseif ($sort === 'priority') {
        // urgent > high > normal > low, rồi mới updated_at desc
        $tickets->orderByRaw("FIELD(priority,'urgent','high','normal','low') ASC")
                ->latest('updated_at');
    } else {
        $tickets->latest('updated_at');
    }

    $tickets = $tickets->paginate(12)->withQueryString();

    

    // Bỏ 'assigned' vì không dùng
    return view('admin.support.index', compact('tickets','q','status','prio','sort'));
}



    public function create()
    {
        // Lấy nhanh vài user để dropdown (có thể đổi sang search ajax)
        $users = User::select('id', 'fullname', 'email')
            ->orderBy('fullname')
            ->limit(50)
            ->get();

        $selectedUser = null;
        if (old('user_id')) {
            $selectedUser = \App\Models\User::select('id', 'fullname', 'email')->find(old('user_id'));
        }
        return view('admin.support.create', compact('users', 'selectedUser'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'      => ['required', 'exists:users,id'],
            'subject'      => ['required', 'string', 'max:255'],
            'category'     => ['required', 'in:order,shipping,refund,product,other'],
            'priority'     => ['nullable', 'in:low,normal,high,urgent'],
            'order_code'   => ['nullable', 'string', 'max:50'],
            'carrier_code' => ['nullable', 'string', 'max:50'],
            'contact_via'  => ['nullable', 'in:phone,email,chat,other'],
            'contact_time' => ['nullable', 'date'],
            'body'         => ['nullable', 'string'],
            'attachments'  => ['nullable', 'array', 'max:10'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx', 'max:4096'],
        ]);

        $admin = $request->user();

        $ticket = DB::transaction(function () use ($data, $request, $admin) {
            // 1) Ticket
            $ticket = SupportTicket::create([
                'user_id'      => $data['user_id'],
                'subject'      => $data['subject'],
                'category'     => $data['category'],
                'priority'     => $data['priority'] ?? 'normal',
                'order_code'   => $data['order_code']   ?? null,
                'carrier_code' => $data['carrier_code'] ?? null,
                'contact_via'  => $data['contact_via']  ?? null,
                'contact_time' => $data['contact_time'] ?? null,
                'status'       => 'open',
            ]);

            // 2) Tin đầu tiên (nếu có) → luôn là admin
            if (($data['body'] ?? null) || $request->hasFile('attachments')) {
                $msg = SupportTicketMessage::create([
                    'support_ticket_id' => $ticket->id,
                    'user_id'           => $admin->id,
                    'is_staff'          => true,
                    'body'              => trim($data['body'] ?? '') !== '' ? $data['body'] : '—',
                ]);

                foreach ($request->file('attachments', []) as $file) {
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

            return $ticket;
        });

        return redirect()->route('admin.support.tickets.show', $ticket)
            ->with('success', 'Tạo phiếu hỗ trợ thành công!');
    }

   public function show(SupportTicket $ticket)
    {
        // Nạp user + messages + attachments + user của message (tránh N+1), theo thứ tự cũ -> mới
        $ticket->load([
            'user:id,fullname,email,username',
            'messages' => fn ($q) => $q->oldest()->with([
                'user:id,fullname,email',
                'attachments:id,support_ticket_message_id,path,original_name,mime'
            ]),
        ]);

        // Danh sách agent (tuỳ quyền của bạn)
        $agents = User::whereIn('role', ['admin']) // hoặc ['admin','support']
            ->get(['id', 'fullname as name', 'email']);

        return view('admin.support.show', compact('ticket', 'agents'));
    }


   public function update(Request $r, SupportTicket $ticket)
{
    $data = $r->validate([
        'status'   => 'nullable|in:open,waiting_staff,waiting_customer,resolved,closed',
        'priority' => 'nullable|in:low,normal,high,urgent',
    ]);

    // Gán kiểu string thuần – tránh mọi DB::raw
    if ($r->filled('status'))   { $ticket->status   = (string) $data['status']; }
    if ($r->filled('priority')) { $ticket->priority = (string) $data['priority']; }
    $ticket->save();

    // Ghi note hệ thống khi có thay đổi
    $sm = \App\Models\SupportTicket::statusMap();
    $pm = \App\Models\SupportTicket::priorityMap();
    $noteParts = [];
    if ($r->filled('status'))   $noteParts[] = "Cập nhật trạng thái: " . ($sm[$r->status] ?? $r->status);
    if ($r->filled('priority')) $noteParts[] = "Cập nhật ưu tiên: " . ($pm[$r->priority] ?? $r->priority);

    if ($noteParts) {
        SupportTicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id'           => Auth::id(),
            'is_staff'          => true,
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
