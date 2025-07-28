<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;

use App\Mail\ContactReplyMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Setting;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */public function index()
    {
        $settings = Setting::all()->keyBy('name'); // key: 'email', 'hotline', 'address',...
        return view('admin.pages.contact', compact('settings'));
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
