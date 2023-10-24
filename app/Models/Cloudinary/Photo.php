<?php

namespace App\Models\Cloudinary;

use App\Models\Contracts\PhotoSource;
use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\NamedTransformation;
use Cloudinary\Transformation\Quality;

class Photo implements PhotoSource
{

    public $original;
    public $photo;
    public $thumb;
    public $banner;

    public function __construct(array $data, Cloudinary $cloudinary)
    {
        // you have to use separate instances here otherwise they combine, and you end up with tiny banner

        $this->original = $data['secure_url'];

        $this->photo = $cloudinary->image($data['public_id'])
            ->namedTransformation(NamedTransformation::name('media_lib_main'))
            ->delivery(Delivery::format(Format::auto()))
            ->delivery(Delivery::quality(Quality::auto()))
            ->toUrl();

        $this->thumb = $cloudinary->image($data['public_id'])
            ->namedTransformation(NamedTransformation::name('media_lib_thumb'))
            ->delivery(Delivery::format(Format::auto()))
            ->delivery(Delivery::quality(Quality::auto()))
            ->toUrl();

        $this->banner = $cloudinary->image($data['public_id'])
            ->namedTransformation(NamedTransformation::name('banner'))
            ->delivery(Delivery::format(Format::auto()))
            ->delivery(Delivery::quality(Quality::auto()))
            ->toUrl();
    }
}