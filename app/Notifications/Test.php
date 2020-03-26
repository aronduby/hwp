<?php

namespace App\Notifications;

use App\Channels\LogChannel;
use App\Notifications\Traits\Loggable;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Twitter\TwitterChannel;
use NotificationChannels\Twitter\TwitterStatusUpdate;

class Test extends Notification implements ShouldQueue
{
    use Loggable, Queueable;

    /**
     * @var string
     */
    public $message;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->sendToLog() ? [LogChannel::class] : [TwitterChannel::class];
    }

    /**
     * Get the log representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toLog($notifiable)
    {
        return [
            'message' => $this->message,
            'context' => []
        ];
    }

    /**
     * Get the twitter status update for this notification.
     *
     * @param $notifiable
     * @return TwitterStatusUpdate
     * @throws \NotificationChannels\Twitter\Exceptions\CouldNotSendNotification
     */
    public function toTwitter($notifiable)
    {
        return new TwitterStatusUpdate($this->message);
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message
        ];
    }
}
