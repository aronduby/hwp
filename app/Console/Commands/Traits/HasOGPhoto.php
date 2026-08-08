<?php

namespace App\Console\Commands\Traits;

use Exception;
use Fusonic\OpenGraph\Consumer AS OpenGraphConsumer;

trait HasOGPhoto
{

    protected function getPhoto($url): string
    {
        $consumer = new OpenGraphConsumer();
        try {
            $data = $consumer->loadUrl($url);
            if (property_exists($data, 'images')
                && count($data->images)
                && $data->images[0]->url !== 'None'
            ) {
                return $data->images[0]->url;
            } else {
                return '';
            }
        } catch (Exception $e) {
            $this->logError($e->getMessage());
            return '';
        }
    }

}
