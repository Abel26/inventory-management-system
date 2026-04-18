<?php

namespace App\Console\Commands;

use App\Jobs\SendMissingWorkLogNotificationJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendMissingWorkLogNotifications extends Command
{
    protected $signature = 'work-logs:send-missing-notifications {date? : Date to check for missing work logs (Y-m-d format). Default: yesterday}';
    protected $description = 'Send notifications to users who have not submitted work logs for the specified date';

    public function handle(): int
    {
        // Get date from argument or use yesterday
        $date = $this->argument('date') 
            ? Carbon::parse($this->argument('date'))->format('Y-m-d')
            : Carbon::yesterday()->format('Y-m-d');

        // Dispatch the job to send notifications
        SendMissingWorkLogNotificationJob::dispatch($date);

        Log::info("Missing work log notification job dispatched for date: {$date}");

        $this->info("Missing work log notification job dispatched for date: {$date}");

        return Command::SUCCESS;
    }
}
