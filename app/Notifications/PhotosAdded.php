<?php /** @noinspection PhpUnusedParameterInspection */

namespace App\Notifications;

use App\Channels\FCMTopicChannel;
use App\Channels\LogChannel;
use App\Models\Recent;
use App\Notifications\Contracts\SendsToFCMTopic;
use App\Notifications\Traits\Loggable;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Kreait\Firebase\Messaging\CloudMessage;

class PhotosAdded extends Notification implements ShouldQueue, SendsToFCMTopic
{
    use Loggable, Queueable;

    /**
     * @var Recent
     */
    protected $recentEntry;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Recent $recentEntry)
    {
        $this->recentEntry = $recentEntry;
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

    public function toLog($notifiable): array
    {
        return [
            'message' => $this->getMessage(),
            'context' => $this->recentEntry
        ];
    }

    public function toFCMTopic(): CloudMessage
    {
        // using withNotification results in fcm code triggering a less nice notification
        // and data can only be a single level with string values, so json encode
        return CloudMessage::new()
            ->withData([
                'notification' => json_encode([
                    'title' => 'New photos!',
                    'body' => $this->getMessage()
                ])
            ]);
    }

    public function getMessage(): string
    {
        $mediaService = resolve('App\Services\MediaServices\MediaService');
        $data = $mediaService->forRecentListing($this->recentEntry, $this->recentEntry->content);
        return "We just added " . $data['count'] . " new photos!";
    }
}
