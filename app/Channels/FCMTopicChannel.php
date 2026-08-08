<?php

namespace App\Channels;

use App\Notifications\Contracts\SendsToFCMTopic;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging;

class FCMTopicChannel
{

    /**
     * @var Messaging $messaging
     */
    protected Messaging $messaging;

    /**
     * @param Messaging $messaging
     */
    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    /**
     * @param $notifiable
     * @param SendsToFCMTopic $notification
     * @return void
     * @throws FirebaseException
     * @throws MessagingException
     */
    public function send($notifiable, SendsToFCMTopic $notification): void
    {
        $topic = $notifiable->routeNotificationFor('FCMTopic', $notification);
        $analyticsLabel = $topic .'.'. strtolower(class_basename($notification));

        $message = $notification->toFCMTopic();
        $message = $message->withTopic($topic);
        $message = $message->withFcmOptions(['analytics_label' => $analyticsLabel]);

        $this->messaging->send($message);
    }
}
