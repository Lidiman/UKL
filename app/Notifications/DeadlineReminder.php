<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
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
            'task_id' => $this->task->id,
            'title' => 'Reminder Task ',
            'message' => 'Task "' . $this->task->title . '" is approaching its deadline on ' . $this->task->due_date->format('Y-m-d H:i:s'),
            'deadline' => $this->task->deadline
        ];
    }
}
