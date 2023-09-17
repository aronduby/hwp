<?php

namespace App\Models\Recent\Render;

use App\Models\Photo;
use App\Services\MediaServices\MediaService;

class Photos extends Renderer
{
    const BG_LIMIT = 20;

    /**
     * The blade template to use
     *
     * @var string
     */
    protected $view = 'recent.photos';

    /**
     * Process the content and save to $this->data
     *
     * @param $content string
     */
    public function process($content)
    {
        /**
         * @var MediaService $mediaService
         */
        $mediaService = resolve('App\Services\MediaServices\MediaService');
        ['photos' => $photos, 'count' => $count] = $mediaService->forRecentListing($content);

        $this->data = [
            'count' => $count,
            'photos' => $photos,
            'recent' => $this->recent
        ];
    }

}