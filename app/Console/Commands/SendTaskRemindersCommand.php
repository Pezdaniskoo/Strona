<?php

namespace App\Console\Commands;

use App\Mail\TaskReminderMail;
use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendTaskRemindersCommand extends Command
{
    protected $signature = 'todo:send-reminders';

    protected $description = 'Send reminder emails for tasks due tomorrow';

    public function handle(): int
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        Task::query()
            ->with('user')
            ->whereDate('due_date', $tomorrow)
            ->where('is_reminder_sent', false)
            ->whereNotIn('status', ['done', 'archived'])
            ->chunkById(100, function ($tasks): void {
                foreach ($tasks as $task) {
                    Mail::to($task->user->email)->send(new TaskReminderMail($task));
                    $task->update(['is_reminder_sent' => true]);
                }
            });

        $this->info('Reminders sent successfully.');

        return self::SUCCESS;
    }
}
