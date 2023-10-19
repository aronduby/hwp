<?php /** @noinspection PhpUnusedParameterInspection */

namespace App\Notifications;

use App\Channels\FCMTopicChannel;
use App\Channels\LogChannel;
use App\Models\Game;
use App\Notifications\Contracts\SendsToFCMTopic;
use App\Notifications\Traits\Loggable;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Kreait\Firebase\Messaging\CloudMessage;

class GameResults extends Notification implements ShouldQueue, SendsToFCMTopic
{
    use Loggable, Queueable;

    /**
     * @var Game $game
     */
    protected $game;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function getMessage(): string
    {
        $status = '';
        switch ($this->game->result) {
            case Game::WIN:
                $status = 'DEFEATS';
                break;

            case Game::LOSS:
                $status = 'LOSES TO';
                break;

            case Game::TIE:
                $status = 'TIES';
                break;
        }

        return sprintf('Hudsonville %s %s %s %d-%d',
            $this->game->team === 'V' ? 'Varsity' : $this->game->team,
            $status,
            $this->game->opponent,
            $this->game->score_us,
            $this->game->score_them,
        );
    }

    public function getTitle(): string
    {
        return 'Final ' . ($this->game->team === 'V' ? 'Varsity' : $this->game->team) . ' Game Results';
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
            'title' => $this->getTitle(),
            'message' => $this->getMessage(),
            'context' => $this->game
        ];
    }

    public function toFCMTopic(): CloudMessage
    {
        // using withNotification results in fcm code triggering a less nice notification
        // and data can only be a single level with string values, so json encode
        return CloudMessage::new()
            ->withData([
                'notification' => json_encode([
                    'title' => $this->getTitle(),
                    'body' => $this->getMessage(),
                    'link' => '/game'.$this->game->id.'/stats',
                ])
            ])
            ->withWebPushConfig([
                'fcm_options' => [
                    'link' => '/game/'.$this->game->id.'/stats'
                ]
            ]);
    }


}
