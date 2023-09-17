<?php

namespace App\Models\Cloudinary;

use App\Models\Contracts\PhotoSource;
use Cloudinary\Cloudinary;
use Cloudinary\Transformation\NamedTransformation;

class Photo implements PhotoSource
{

    public $photo;
    public $thumb;
    public $banner;

    public function __construct(array $data, Cloudinary $cloudinary)
    {
        // you have to use separate instances here otherwise they combine, and you end up with tiny banner

        $this->photo = $data['secure_url'];
        $this->thumb = $cloudinary->image($data['public_id'])
            ->namedTransformation(NamedTransformation::name('media_lib_thumb'))
            ->toUrl();
        $this->banner = $cloudinary->image($data['public_id'])
            ->namedTransformation(NamedTransformation::name('banner'))
            ->toUrl();
    }
}