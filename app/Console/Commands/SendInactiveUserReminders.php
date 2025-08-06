<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendInactiveUserReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
    */
    protected $signature = 'users:send-inactive-reminders';

    /**
     * The console command description.
     *
     * @var string
    */
    protected $description = 'Send email reminders to users who have been inactive for more than 30 days.';

    /**
     * Execute the console command.
    */
    public function handle()
    {
        $inactiveUsers = User::where('is_active', false)
            ->where('updated_at', '<', now()->subDays(30)) // Example: Inactive for 30 days
            ->get();

        foreach ($inactiveUsers as $user) {
            // Send them an email
            // We'll create this mailable
            Mail::to($user->email)->send(new \App\Mail\InactiveUserReminder($user));
            $this->info("Reminder sent to: {$user->email}");
        }

        $this->info('Inactive user reminder task completed successfully.');
    }
}