<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailCampaign;
use App\Models\User;
use App\Mail\CampaignMail;
use Illuminate\Support\Facades\Mail;

class EmailCampaignController extends Controller
{
    public function index()
    {
        $campaigns = EmailCampaign::latest()->get();
        return view('admin.email_campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $users = User::select('id', 'username', 'email')->get()->map(function ($u) {
            return [
                'value' => $u->email,
                'name' => $u->username,
                'id' => $u->id,
                'email' => $u->email,
                'display' => "ID: {$u->id} | Username: {$u->username} | Email: {$u->email}"
            ];
        });

        return view('admin.email_campaigns.create', compact('users'));
    }

    public function getRecipients()
    {
        return response()->json(User::pluck('email'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaign_name' => 'required|string|max:255',
            'email_subject' => 'required|string|max:255',
            'email_body' => 'required|string', // Nội dung HTML
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'users' => 'required|string', // JSON Tagify
        ]);

        // Parse và validate danh sách người nhận
        $tagifyUsers = json_decode($request->users, true);
        $emails = [];

        foreach ($tagifyUsers as $item) {
            foreach (explode(',', $item['value']) as $email) {
                $email = trim($email);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emails[] = $email;
                }
            }
        }

        $emails = array_unique($emails);

        // Lưu chiến dịch
        $campaign = EmailCampaign::create([
            'campaign_name'   => $request->campaign_name,
            'email_subject'   => $request->email_subject,
            'email_body'      => $request->email_body, // HTML
            'cc'              => $request->cc,
            'bcc'             => $request->bcc,
            'target_emails'   => $emails,
            'status'          => 'Đã gửi',
        ]);

        // Gửi email
        foreach ($emails as $email) {
            $mail = Mail::to($email);

            if (!empty($request->cc)) {
                $mail->cc(explode(',', $request->cc));
            }

            if (!empty($request->bcc)) {
                $mail->bcc(explode(',', $request->bcc));
            }

            $mail->send(new CampaignMail(
                $request->email_subject,
                $request->email_body // Gửi HTML
            ));
        }

        return redirect()->route('admin.email_campaigns.index')->with('success', 'Tạo và gửi chiến dịch thành công!');
    }

    public function edit($id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        return view('admin.email_campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, $id)
    {
        $campaign = EmailCampaign::findOrFail($id);

        $request->validate([
            'campaign_name' => 'required|string|max:255',
            'email_subject' => 'required|string|max:255',
            'email_body'    => 'required|string',
            'cc'            => 'nullable|string',
            'bcc'           => 'nullable|string',
        ]);

        $campaign->update([
            'campaign_name' => $request->campaign_name,
            'email_subject' => $request->email_subject,
            'email_body'    => $request->email_body,
            'cc'            => $request->cc,
            'bcc'           => $request->bcc,
        ]);

        return redirect()->route('admin.email_campaigns.index')->with('success', 'Cập nhật chiến dịch thành công!');
    }

    public function destroy($id)
    {
        EmailCampaign::findOrFail($id)->delete();
        return redirect()->route('admin.email_campaigns.index')->with('success', 'Đã xóa chiến dịch!');
    }
}
