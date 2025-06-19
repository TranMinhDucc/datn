<?php

namespace App\Http\Controllers\Client;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Mail\ContactReplyMail;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        // Lấy danh sách sản phẩm, có thể dùng paginate

        return view('client.contact.index');
    }
    public function store(Request $request)
{
     // Validate
    $validated = $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'subject' => 'required',
        'message' => 'required',
    ]);

    // Lưu vào database
    $contact = Contact::create($validated);

    // ✅ Gửi mail tự động sau khi lưu
    Mail::to($contact->email)->send(new ContactReplyMail($contact, $contact->message));
    $contact->update([
        'statusreply' => true,
        'replied_at' => Carbon::now(),
    ]);
    return redirect()->back()->with('success', 'Cảm ơn bạn đã liên hệ!');
}
}
