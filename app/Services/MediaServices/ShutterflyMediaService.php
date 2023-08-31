<?php

namespace App\Services\MediaServices;

use App\Services\MediaServices\LocalMediaService;
use App\Services\MediaServices\MediaService;

class ShutterflyMediaService extends LocalMediaService implements MediaService
{


    public function getScript(): string
    {
        return 'js/gallery/shutterfly.js';
    }
}