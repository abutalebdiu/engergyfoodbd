<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;

use App\Models\Mail\MailBox;
use App\Models\Mail\Category;
use App\Models\Mail\DomainConfig;
use App\Models\Mail\History;
use App\Models\Mail\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use App\Traits\MailConfigTrait;
use App\Jobs\SendEmailJob;
use App\Traits\FileUpload;

class SendMailController extends Controller
{
    use FileUpload;

    public function index()
    {
        $data['domainconfigs'] = DomainConfig::get();
        return view('mail_vendor.mail.send', $data);
    }


    public function indexsendmail()
    {
        $data['domainconfigs'] = DomainConfig::get();
        return view('mail_vendor.mail.groupmailsend', $data);
    }


    public function store(Request $request)
    {
        $request->validate([
            "domain" => "required",
            "email_address" => "required",
            "subject" => "required",
            "template" => "nullable",
            "message" => "required",
            "attachment" => "nullable|array",
        ]);


        $email_lists = [];

        if ($request->input('email_address')) {
            $emails = $request->input('email_address');
            $email_lists = explode(',', $emails);

            $validatedEmails = array_filter($email_lists, function ($email) {
                return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
            });

            if (count($email_lists) !== count($validatedEmails)) {
                return redirect()->back()->with('error', 'Invalid email address(es) provided.');
            }
        }

        $history = $this->historySave($request, $email_lists);

        if ($history) {
            Artisan::call('test:email', ['job' => $history->id]);
            return back()->with(['error' => 'Mail sending processing, wait for a few minutes']);
        } else {
            return back()->with(['error' => 'Mail send fail. Please try again or later']);
        }
    }

    public function sendGroupMail(Request $request)
    {
        $request->validate([
            "domain" => "required|exists:domain_configs,id",
            "category_id" => "required|exists:categories,id",
            "subject" => "required",
            "receiver" => "required",
            "message" => "required",
            "attachment" => "nullable|array",
        ]);

        $history = $this->historySave($request, null);
        if ($history) {
            Artisan::call('test:email', ['job' => $history->id]);
            return back()->with(['error' => 'Mail sending processing, wait for a few minutes']);
        } else {
            return back()->with(['error' => 'Mail send fail. Please try again or later']);
        }
    }


    protected function attachFile($request)
    {
        if ($request->attachment) {
            $attachment_file = [];
            foreach ($request->attachment as $attach) {
                $attachment_file[] = $this->fileUpload($attach);
            }
            return $attachment_file;
        } else {
            return '';
        }
    }


    public function historySave($request, $email_lists = null)
    {
        try {
            $history = new History();
            $history->type = 'email';
            $history->subject = $request->subject;
            $history->message = json_encode($request->message);
            $history->email = json_encode($email_lists);
            $history->domain = $request->domain;
            $history->attachment = json_encode($this->attachFile($request));
            $history->status = 0;
            $history->save();

            return $history;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function mailhistory()
    {
        $data['mail_histories'] = History::where('type', 'email')->latest()->paginate();
        return view('mail_vendor.mail.history', $data);
    }
}
