<?php

namespace App\Notifications;

use App\Channels\LogChannel;
use App\Models\Article;
use App\Notifications\Traits\Loggable;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Twitter\TwitterChannel;
use NotificationChannels\Twitter\TwitterStatusUpdate;

class ArticleImported extends Notification implements ShouldQueue
{
    use Loggable, Queueable;

    /**
     * @var Article
     */
    protected $article;

    /**
     * Create a new notification instance.
     *
     * @param Article $article
     * @return void
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function toLog($notifiable)
    {
        return [
            'message' => $this->getMessage(),
            'context' => $this->article->toArray()
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
        return new TwitterStatusUpdate($this->getMessage());
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
            //
        ];
    }

    protected function getMessage() {
        return trans('notifications.articleImported', [
            'title' => $this->article->title,
            'url' => $this->article->url
        ]);
    }
}
