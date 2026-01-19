<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MailBox;
use App\Jobs\SendEmailJob;
use App\Jobs\SendSmsJob;
use Config;

class SMSCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:smstest  {job}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $job = $this->argument('job');

        if ($job) {
            dispatch(new SendSmsJob($job));
        }
    }
}
