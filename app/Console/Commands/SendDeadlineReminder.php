<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Notifications\DeadlineReminder;
use Carbon\Carbon;

class SendDeadlineReminder extends Command
{
    protected $signature = 'deadline:reminder';
    protected $description = 'Send reminder for projects deadline tomorrow';

public function handle()
{
    $tomorrow = Carbon::tomorrow();

    $tasks = Task::whereDate('due_date', $tomorrow)->where('status', '!=', 'completed')->get();

    $this->info('Jumlah task ketemu: ' . $tasks->count());

    foreach ($tasks as $task) {

        if (!$task->user) {
            $this->info('Task tanpa user, skip');
            continue;
        }

        $alreadySent = $task->user->notifications()
            ->where('data->task_id', $task->id)
            ->exists();

        if (!$alreadySent) {
            $task->user->notify(new DeadlineReminder($task));
            $this->info('Notif dikirim untuk task ID: ' . $task->id);
        }
    }

    $this->info('Deadline reminder selesai!');
}
}