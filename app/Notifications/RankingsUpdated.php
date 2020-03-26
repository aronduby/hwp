<?php

namespace App\Notifications;

use App\Channels\LogChannel;
use App\Models\Rank;
use App\Notifications\Traits\Loggable;
use App\Providers\MiscDirectiveServiceProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Twitter\TwitterChannel;
use NotificationChannels\Twitter\TwitterStatusUpdate;

class RankingsUpdated extends Notification implements ShouldQueue
{
    use Loggable, Queueable;

    /**
     * @var Rank
     */
    public $newRank;


    /**
     * @var Rank
     */
    public $lastRank;

    /**
     * Create a new notification instance.
     *
     * @param Rank $newRank
     * @param Rank $lastRank
     */
    public function __construct(Rank $newRank = null, Rank $lastRank = null)
    {
        $this->newRank = $newRank;
        $this->lastRank = $lastRank;
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
            'message' => $this->getMessage(),
            'context' => [
                $this->newRank->toArray(),
                $this->lastRank->toArray()
            ]
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

    public function getMessage() {
        $moved = '';
        $tied = '';
        $rank = '';
        $punc = '.';

        if ($this->newRank && $this->newRank->tied) {
            $tied = trans('notifications.tiedFor');
        }

        // previously ranked but dropped off the rankings
        if (!$this->newRank && $this->lastRank) {
            $moved = trans('notifications.movedDown');
            $rank = trans('notifications.unranked');

        // unranked to ranked
        // moved up
        } elseif (
            (!$this->lastRank && $this->newRank)
            || $this->lastRank->rank > $this->newRank->rank
        ) {
            $moved = $this->lastRank ? trans('notifications.movedUp') : trans('notifications.were');
            $rank = MiscDirectiveServiceProvider::ordinal($this->newRank->rank);
            $punc = '!';

        // moved down but still ranked
        } elseif($this->lastRank->rank < $this->newRank->rank) {
            $moved = trans('notifications.movedDown');
            $rank = MiscDirectiveServiceProvider::ordinal($this->newRank->rank);

        // stayed the same
        } elseif ($this->lastRank->rank == $this->newRank->rank) {
            if ($this->lastRank->tied && $this->newRank->tied) {
                $moved = trans('notifications.stayed');
                $tied = trans('notifications.tiedFor');
            } elseif ($this->lastRank->tied != $this->newRank->tied) {
                $moved = trans('notifications.were');
                $tied = $this->newRank->tied ? trans('notifications.tiedFor') : '';
            } else {
                $moved = trans('notifications.stayed');
            }

            $punc = '!';
            $rank = MiscDirectiveServiceProvider::ordinal($this->newRank->rank);
        }

        return trans('notifications.rankings', [
            'moved' => $moved,
            'tied' => $tied,
            'rank' => $rank,
            'punc' => $punc
        ]);
    }
}
