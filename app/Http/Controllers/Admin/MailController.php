<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminCustomMail;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function __construct(private readonly AuditLogService $audit)
    {
    }

    public function index()
    {
        $users = User::query()->orderBy('email')->get(['id', 'email']);

        return view('admin.mail.index', compact('users'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string'],
        ]);

        $user = User::findOrFail($data['user_id']);
        Mail::to($user->email)->send(new AdminCustomMail($data['subject'], $data['message']));

        $this->audit->log($request, 'send-mail', 'user', $user->id, 'Sent custom email from admin panel');

        return back()->with('success', __('messages.mail_sent'));
    }
}
