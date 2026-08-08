<?php

namespace App\Notifications\Traits;

trait Loggable
{

    /**
     * Should we be sending to the log instead of the normal methods?
     *
     * @return bool
     */
    public function sendToLog(): bool
    {
        return env('LOG_NOTIFICATIONS');
    }

}
