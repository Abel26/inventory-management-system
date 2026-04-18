<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\WorkLog;
use App\Notifications\MissingWorkLogNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendMissingWorkLogNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $date;

    public function __construct(string $date)
    {
        $this->date = $date;
    }

    public function handle(): void
    {
        // Get users who haven't submitted work log for the specified date
        $usersWithoutWorkLog = User::whereHas('roles', function ($query) {
                $query->where('name', '!=', 'super_admin');
            })
            ->whereDoesntHave('workLogs', function ($query) {
                $query->whereDate('work_date', $this->date);
            })
            ->where('is_active', true)
            ->get();

        // Send notification to each user
        foreach ($usersWithoutWorkLog as $user) {
            $user->notify(new MissingWorkLogNotification($user, $this->date));
        }

        // Log notification sent
        Log::info("Missing work log notifications sent for date: {$this->date}", [
            'count' => $usersWithoutWorkLog->count(),
            'users' => $usersWithoutWorkLog->pluck('id')->toArray(),
        ]);
    }
}
