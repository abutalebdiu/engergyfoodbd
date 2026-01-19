<?php

namespace App\Jobs;

use App\Models\Mail\History;
use App\Models\Mail\Category;
use App\Traits\SmsTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Traits\MailConfigTrait;
use App\Mail\EmailTemplate;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MailConfigTrait, SmsTrait;

    public $history_id;

    /**
     * Create a new job instance.
     *
     * @param int $history_id
     * @return void
     */
    public function __construct($history_id)
    {
        $this->history_id = $history_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = History::where("id", $this->history_id)->first();

        if($data) {
            if($data->group_id){
                try {
                    $contactGroup = Category::with('contacts')->findOrFail($data->group_id);

                    foreach ($contactGroup->contacts as $contact) {
                        $mailConfig = $this->mailConfig($data->domain);
                        Config::set($mailConfig);

                        $short_codes = [
                            "name"  => $contact->name,
                        ];

                        $message = $this->templateConfig($data->template_code, $short_codes);

                        Mail::mailer($mailConfig['driver'])->to($contact->email)->send(new EmailTemplate($message));
                        sleep(90);
                    }
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            } else {
                $this->handleMailSend($data);
            }
        } else {
            Log::info("No history found with id: " . $this->history_id);
        }

    }

    protected function handleMailSend($history)
    {
        foreach (json_decode($history->email, true) as $email) {
            $mailConfig = $this->mailConfig($history->domain);
            Config::set('mail', $mailConfig); // Update mail configuration
    
            $message = [
                'title'         => $history->title,
                'subject'       => $history->subject,
                'message_body'  => $history->message_body,
            ];
    
            // Send email using the specified mail driver
            Mail::mailer($mailConfig['driver'])->to($email)->send(new EmailTemplate($message));
    
            sleep(90); // Sleep for 90 seconds
        }
    }
    
}
