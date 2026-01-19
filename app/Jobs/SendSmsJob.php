<?php

namespace App\Jobs;

use App\Models\Mail\History;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Mail\MailBox;
use Illuminate\Support\Facades\Log;
use App\Traits\SmsTrait;
use App\Services\SmsService;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SmsTrait;

    protected $history;


    /**
     * Create a new job instance.
     *
     * @param int $mailGroupId
     * @return void
     */
    public function __construct($history)
    {
        $this->history = $history;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $history = History::findOrFail($this->history);
            $smsService = new SmsService();

            $short_codes = [];
            $message = $this->templateConfig($history->template_code, $short_codes);

            if ($history->group_id != null) {
                $phone = $smsService->getPhones($history->group_id);
                return $smsService->sendMessage($history->code, $phone, $message);
            } else {
                return $smsService->sendMessage($history->code, $history->phone, $message);
            }

        } catch (\Exception $e) {
            Log::error("Failed to send sms to: " . $e->getMessage());

        }
    }


}
