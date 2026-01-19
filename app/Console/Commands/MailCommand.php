<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MailBox;
use App\Jobs\SendEmailJob;
use Config;
use Illuminate\Support\Facades\Log;

class MailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email  {job}';

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
            dispatch(new SendEmailJob($job));
        }
    }
}
