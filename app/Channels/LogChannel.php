<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class LogChannel
{

    /**
     * Log the given notification
     *
     * @param $notifiable
     * @param Notification $notification
     */
    public function send($notifiable, Notification $notification): void
    {
        $data = $notification->toLog($notifiable);

        $class = get_class($notifiable);
        $key = $notifiable->getKey();
        $notificationclass = get_class($notification);
        $message = $data['message'];

        $msg = trans('notifications.log', compact(
            'class',
            'key',
            'notificationclass',
            'message'
        ));

        Log:info($msg, $data['context']);
    }

}
