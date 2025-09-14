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
        $topic = $notifiable->routeNotificationFor('FCMTopic', $notification);
        $analyticsLabel = $topic .'.'. strtolower(class_basename($notification));

        $message = $notification->toFCMTopic();
        $message = $message->withChangedTarget('topic', $topic);
        $message = $message->withFcmOptions(['analytics_label' => $analyticsLabel]);

        $this->messaging->send($message);
    }
}