<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Constants\Status;
use App\Models\SupportMessage;
use App\Traits\SupportManager;
use App\Http\Controllers\Controller;
use App\Models\Setting\SupportTicket;

class SupportController extends Controller
{
    use SupportManager;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('admin')->user();
            return $next($request);
        });

        $this->userType = 'admin';
        $this->column = 'admin_id';
    }

    public function create()
    {

        return view('admin.support.create');
    }


    public function tickets()
    {
        $title = 'All Support';
        $items = SupportTicket::orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'title'));
    }

    public function pendingSupport()
    {
        $title = 'Pending Supports';
        $items = SupportTicket::whereIn('status', [Status::SUPPORT_OPEN, Status::SUPPORT_REPLY])->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'title'));
    }

    public function closedSupport()
    {
        $title = 'Closed Supports';
        $items = SupportTicket::where('status', Status::SUPPORT_CLOSE)->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'title'));
    }

    public function answeredSupport()
    {
        $title = 'Answered Supports';
        $items = SupportTicket::orderBy('id', 'desc')->with('user')->where('status', Status::SUPPORT_ANSWER)->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'title'));
    }

    public function supportReply($id)
    {
        $ticket = SupportTicket::with('user')->where('id', $id)->firstOrFail();
        $messages = SupportMessage::with('ticket', 'admin')->where('support_ticket_id', $ticket->id)->orderBy('id', 'desc')->get();
        return view('admin.support.reply', compact('ticket', 'messages'));
    }

    public function ticketDelete($id)
    {
        $message = SupportMessage::findOrFail($id);
        $path = getFilePath('ticket');
        if ($message->attachments()->count() > 0) {
            foreach ($message->attachments as $attachment) {
                fileManager()->removeFile($path . '/' . $attachment->attachment);
                $attachment->delete();
            }
        }
        $message->delete();
        $notify[] = ['success', "Support deleted successfully"];
        return back()->withNotify($notify);

    }
}
