<?php

namespace App\Notifications\Contracts;

use Kreait\Firebase\Messaging\CloudMessage;

interface SendsToFCMTopic
{
    public function toFCMTopic(): CloudMessage;
}