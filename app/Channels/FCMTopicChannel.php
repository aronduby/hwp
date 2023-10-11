<?php

namespace App\Channels;

use App\Notifications\Contracts\SendsToFCMTopic;
use Kreait\Firebase\Messaging;

class FCMTopicChannel
{

    /**
     * @var Messaging $messaging
     */
    protected $messaging;

    /**
     * @param Messaging $messaging
     */
    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }


    public function send($notifiable, SendsToFCMTopic $notification)
    {
        $message = $notification->toFCMTopic();
        $topic = $notifiable->routeNotificationFor('FCMTopic', $notification);
        $message = $message->withChangedTarget('topic', $topic);

        $this->messaging->send($message);
    }
}