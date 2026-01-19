<?php

namespace App\Http\Controllers\Admin\Setting;


use Illuminate\Http\Request;
use App\Models\Setting\Subscriber;
use App\Http\Controllers\Controller;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.subscriber.index', compact('subscribers'));
    }

    public function sendEmailForm()
    {
        return view('admin.subscriber.send_email');
    }

    public function remove($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();

        $notify[] = ['success', 'Subscriber deleted successfully'];
        return back()->withNotify($notify);
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'body' => 'required',
        ]);
        $subscribers = Subscriber::cursor();
        foreach ($subscribers as $subscriber) {
            $receiverName = explode('@', $subscriber->email)[0];
            $user = [
                'username' => $subscriber->email,
                'email' => $subscriber->email,
                'fullname' => $receiverName,
            ];
            notify($user, 'DEFAULT', [
                'subject' => $request->subject,
                'message' => $request->body,
            ], ['email']);
        }
        $notify[] = ['success', 'Email will be send to all subscribers'];
        return back()->withNotify($notify);
    }
}
