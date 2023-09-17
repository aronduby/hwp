<?php

namespace App\Models\Cloudinary;

use App\Models\Contracts\PhotoSource;
use Cloudinary\Cloudinary;
use Cloudinary\Transformation\NamedTransformation;

class Photo implements PhotoSource
{

    public $photo;
    public $thumb;

    public function __construct(array $data, Cloudinary $cloudinary)
    {
        $cImg = $cloudinary->image($data['public_id']);

        $this->photo = $data['secure_url'];
        $this->thumb = $cImg->namedTransformation(NamedTransformation::name('media_lib_thumb'))->toUrl();
    }
}