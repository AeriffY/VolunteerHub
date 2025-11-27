<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Activity;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivityNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $activity;
    protected $subject;
    protected $message;

    public function __construct(Activity $activity, $subject, $message)
    {
        $this->activity = $activity;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('[志愿汇通知]' . $this->subject)
                    ->greeting('您好, ' . $notifiable->name . ': ')
                    ->line('您报名的活动' ."\"". $this->activity->title . "\"".'有一条新消息：')
                    ->line($this->message)
                    ->action('查看活动详情', route('activities.show', $this->activity->id))
                    ->line('请准时参加或留意变更信息.');
    }
}