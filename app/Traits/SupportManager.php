<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Models\SupportMessage;

use App\Models\AdminNotification;
use App\Models\SupportAttachment;
use App\Models\Setting\SupportTicket;

trait SupportManager
{
    protected $files;
    protected $allowedExtension = ['jpg', 'png', 'jpeg', 'pdf', 'doc', 'docx'];
    protected $userType;
    protected $user = null;
    protected $column;

    public function supportTicket()
    {
        $user = $this->user;
        if (!$user) {
            abort(404);
        }
        $pageTitle = "Supports";
        $supports = SupportTicket::where($this->column, $user->id)->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->userType . '.support.index', compact('supports', 'pageTitle'));
    }

    public function openSupport()
    {
        $user = $this->user;

        if (!$user) {
            return to_route('home');
        }
        $title = "Open Support";
        return view($this->userType . '.support.create', compact('title', 'user'));
    }

    public function storeSupport(Request $request)
    {
        $user = $this->user;

        if (!$user) {
            return to_route('home');
        }

        $ticket  = new SupportTicket();
        $message = new SupportMessage();

        $this->validation($request);

        $column             = $this->column;
        $user               = $this->user;
        $ticket->$column    = $user->id;
        $ticket->ticket     = rand(100000, 999999);
        $ticket->name       = $user->name;
        $ticket->email      = $user->email;
        $ticket->subject    = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status     = Status::SUPPORT_OPEN;
        $ticket->save();


        $message->support_ticket_id   = $ticket->id;
        $message->message             = $request->message;
        $message->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->$column   = $user->id;
        $adminNotification->title     = 'New support has opened';
        $adminNotification->click_url = urlPath('admin.support.view', $ticket->id);
        $adminNotification->save();

        if ($request->hasFile('attachments')) {
            $uploadAttachments = $this->storeSupportAttachments($message->id);
            if ($uploadAttachments != 200) return back()->withNotify($uploadAttachments);;
        }

        $notify[] = ['success', 'Support opened successfully!'];


        return to_route($this->redirectLink, $ticket->ticket)->withNotify($notify);
    }

    public function viewSupport($number)
    {
        $user      = $this->user;
        $column    = $this->column;
        $userId    = 0;
        $layout    = $this->layout;

        $myTicket = SupportTicket::where('ticket', $number)->orderBy('id', 'desc')->firstOrFail();

        if ($myTicket->$column > 0) {
            if ($user) {
                $userId = $user->id;
            } else {
                return to_route($this->userType . '.login');
            }
        }

        $myTicket = SupportTicket::where('ticket', $number)->where($this->column, $userId)->orderBy('id', 'desc')->firstOrFail();
        $messages = SupportMessage::where('support_ticket_id', $myTicket->id)->with('ticket', 'admin', 'attachments')->orderBy('id', 'desc')->get();

        return view($this->userType . '.support.view', compact('myTicket', 'messages', 'user', 'layout'));
    }


    public function replySupport(Request $request, $id)
    {
        $user = $this->user;
        $userId = 0;
        if ($user) {
            $userId = $user->id;
        }
        $ticket = SupportTicket::where('id', $id)->firstOrFail();
        if (($this->userType == 'user') && ($userId != $ticket->user_id)) {
            abort(404);
        }
        $message = new SupportMessage();

        $request->merge(['SUPPORT_REPLY' => 1]);

        $this->validation($request);

        $ticket->status = $this->userType != 'admin' ? Status::SUPPORT_REPLY : Status::SUPPORT_ANSWER;
        $ticket->last_reply = Carbon::now();
        $ticket->save();
        $message->support_ticket_id = $ticket->id;
        if ($this->userType == 'admin') {
            $message->admin_id = $user->id;
        }

        $message->message = $request->message;
        $message->save();

        if ($request->hasFile('attachments')) {
            $uploadAttachments = $this->storeSupportAttachments($message->id);
            if ($uploadAttachments != 200) return back()->withNotify($uploadAttachments);;
        }

        if ($this->userType == 'admin') {
            $createLog = false;
            $user = $ticket;
            if ($ticket->user_id != 0) {
                $createLog = true;
                $user = $ticket->user;
            }

            notify($user, 'ADMIN_SUPPORT_REPLY', [
                'ticket_id' => $ticket->ticket,
                'ticket_subject' => $ticket->subject,
                'reply' => $request->message,
                'link' => route('support.view', $ticket->ticket),
            ], null, $createLog);
        }


        $notify[] = ['success', 'Support replied successfully!'];

        return back()->withNotify($notify);
    }

    protected function storeSupportAttachments($messageId)
    {
        $path = getFilePath('ticket');

        foreach ($this->files as  $file) {
            try {
                $attachment = new SupportAttachment();
                $attachment->support_message_id = $messageId;
                $attachment->attachment = fileUploader($file, $path);
                $attachment->save();
            } catch (\Exception $exp) {
                $notify[] = ['error', 'File could not upload'];
                return $notify;
            }
        }

        return 200;
    }

    protected function validation($request)
    {
        $maxSize = substr(ini_get('upload_max_filesize'), 0, -1);
        $this->maxSize = $maxSize;
        $this->files = $request->file('attachments');

        $request->validate([
            'attachments' => [
                'max:4096',
                function ($attribute, $value, $fail) {
                    foreach ($this->files as $file) {
                        $ext = strtolower($file->getClientOriginalExtension());
                        if (($file->getSize() / 1000000) > $this->maxSize) {
                            return $fail("Maximum $this->maxSize MB file size allowed!");
                        }
                        if (!in_array($ext, $this->allowedExtension)) {
                            return $fail("Only png, jpg, jpeg, pdf, doc, docx files are allowed");
                        }
                    }
                    if (count($this->files) > 5) {
                        return $fail("Maximum 5 files can be uploaded");
                    }
                },
            ],
            'subject'   => 'required_without:SUPPORT_REPLY|max:255',
            'message'   => 'required',
        ]);
    }

    public function closeSupport($id)
    {
        $user = $this->user;
        $ticket = SupportTicket::where('id', $id)->firstOrFail();
        if ($this->userType != 'admin') {
            $column = $this->column;
            if ($user->id != $ticket->$column) {
                abort(403);
            }
        }

        $ticket->status = Status::SUPPORT_CLOSE;
        $ticket->save();
        $notify[] = ['success', 'Support closed successfully!'];
        return back()->withNotify($notify);
    }

    public function supportDownload($attachmentId)
    {
        $attachment = SupportAttachment::findOrFail(decrypt($attachmentId));
        $file = $attachment->attachment;
        $path = getFilePath('ticket');
        $full_path = $path . '/' . $file;
        $title = slug($attachment->supportMessage->ticket->subject);
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }
}
