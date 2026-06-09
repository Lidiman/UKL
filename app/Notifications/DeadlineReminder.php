<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class DeadlineReminder extends Notification
{
    protected $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'task_id'  => $this->task->id,
            'title'    => 'Deadline Approaching',
            'message'  => 'Task "' . $this->task->title . '" is due tomorrow on ' . $this->task->due_date,
            'due_date' => $this->task->due_date,
            'priority' => $this->task->priority,
            'type'     => 'deadline_reminder',
        ];
    }
}
