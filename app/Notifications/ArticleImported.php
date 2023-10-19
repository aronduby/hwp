<?php /** @noinspection PhpUnusedParameterInspection */

namespace App\Notifications;

use App\Channels\FCMTopicChannel;
use App\Channels\LogChannel;
use App\Models\Article;
use App\Notifications\Contracts\SendsToFCMTopic;
use App\Notifications\Traits\Loggable;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Kreait\Firebase\Messaging\CloudMessage;

class ArticleImported extends Notification implements ShouldQueue, SendsToFCMTopic
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
    public function via($notifiable): array
    {
        return $this->sendToLog() ? [LogChannel::class] : [FCMTopicChannel::class];
    }

    /**
     * Get the log representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toLog($notifiable): array
    {
        return [
            'message' => $this->getMessage(),
            'context' => $this->article->toArray()
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
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

    public function toFCMTopic(): CloudMessage
    {
        // using withNotification results in fcm code triggering a less nice notification
        // and data can only be a single level with string values, so json encode
        return CloudMessage::new()
            ->withData([
                'notification' => json_encode([
                    'title' => 'New Article Imported',
                    'body' => 'We just imported a new article: ' . $this->article->title,
                    'link' => $this->article->url
                ])
            ])
            ->withWebPushConfig([
                'fcm_options' => [
                    'link' => $this->article->url
                ]
            ]);
    }
}
