<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;

use App\Mail\ContactReplyMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $contacts = Contact::latest()->paginate(10);
    return view('admin.contacts.index', compact('contacts'));
}

public function show($id)
{
    $contact = Contact::findOrFail($id);
    return view('admin.contacts.show', compact('contact'));
}

public function reply(Request $request, $id)
{
    $contact = Contact::findOrFail($id);
    $replyContent = $request->input('replyContent');

    Mail::to($contact->email)->send(new ContactReplyMail($contact, $replyContent));

    $contact->statusreply = true;
    $contact->replied_at = now();
    $contact->save();

    return redirect()->route('admin.contacts.index')->with('success', 'Đã gửi phản hồi thành công ');

}
}
