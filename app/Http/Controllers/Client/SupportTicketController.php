<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;   // lưu ý import base Controller
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\SupportMessageAttachment;
use App\Models\Order;

class SupportTicketController extends Controller
{

    public function index(Request $request)
    {
        $q      = trim($request->input('q', ''));
        $status = $request->input('status', '');
        $sort   = $request->input('sort', 'latest'); // latest|oldest|priority

        $tickets = \App\Models\SupportTicket::where('user_id', \Auth::id())
            ->when($q, function ($qr) use ($q) {
                $qr->where(function ($xx) use ($q) {
                    $xx->where('subject', 'like', "%{$q}%")
                        ->orWhere('order_code', 'like', "%{$q}%")
                        ->orWhere('id', $q);
                });
            })
            ->when($status, fn($qr) => $qr->where('status', $status));

        // sắp xếp
        if ($sort === 'oldest') {
            $tickets->oldest('updated_at');
        } elseif ($sort === 'priority') {
            // urgent > high > normal
            $tickets->orderByRaw("FIELD(priority,'urgent','high','normal') ASC")
                ->latest('updated_at');
        } else {
            $tickets->latest('updated_at');
        }

        $tickets = $tickets->paginate(10)->withQueryString();

        return view('client.support.index', compact('tickets', 'q', 'status', 'sort'));
    }


    public function create()
    {
        // change 'order_code as code' if you prefer order_code in the view
        $orders = Order::where('user_id', Auth::id())
            ->latest('created_at')
            ->take(10)
            ->get(['order_code as code', 'status', 'created_at']);

        return view('client.support.create', compact('orders'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'subject'       => 'required|string|max:120',
            'category'      => 'required|in:order,product,payment,account,other',
            'priority'      => 'required|in:normal,high,urgent',
            'order_code'    => 'nullable|string|max:100',
            'carrier_code'  => 'nullable|string|max:100',
            'body'          => 'required|string|min:20',
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp,mp4,pdf',
        ]);

        $ticket = SupportTicket::create([
            'user_id'      => Auth::id(),
            'subject'      => $data['subject'],
            'category'     => $data['category'],
            'priority'     => $data['priority'],
            'order_code'   => $data['order_code'] ?? null,
            'carrier_code' => $data['carrier_code'] ?? null,
            // 'contact_via'  => [],
            // 'contact_time' => null,
            'status'       => 'open',
        ]);


        // tin nhắn đầu tiên = mô tả ban đầu
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

        return redirect()->route('support.tickets.thread.show', $ticket)
            ->with('success', 'Đã tạo phiếu hỗ trợ!');
    }
}
