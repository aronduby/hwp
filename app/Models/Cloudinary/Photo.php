<?php

namespace App\Models\Cloudinary;

use App\Models\Contracts\PhotoSource;
use App\Services\MediaServices\CloudinaryMediaService;
use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\NamedTransformation;
use Cloudinary\Transformation\Quality;
use JsonSerializable;

class Photo implements PhotoSource, JsonSerializable
{

    public string $original;
    public string $photo;
    public string $thumb;
    public string $banner;

    public string $width;
    public string $height;

    /**
     * @param array{
     *     width: int,
     *     height: int,
     *     aspect_ratio: float,
     *     secure_url: string,
     *     public_id: string
     * } $data The data array describing an image in cloudinary
     * @param Cloudinary $cloudinary
     */
    public function __construct(array $data, Cloudinary $cloudinary)
    {
        // you have to use separate instances here otherwise they combine, and you end up with tiny banner

        $this->original = $data['secure_url'];
        if ($data['width'] >= $data['height']) {
            $this->width = min($data['width'], self::MAIN_MAX_W);
            $this->height = round($this->width / $data['aspect_ratio']);
        } else {
            $this->height = min($data['height'], self::MAIN_MAX_H);
            $this->width = round($this->height / $data['aspect_ratio']);
        }

        $this->photo = $cloudinary->image($data['public_id'])
            ->namedTransformation(NamedTransformation::name(CloudinaryMediaService::T_MAIN))
            ->delivery(Delivery::format(Format::auto()))
            ->delivery(Delivery::quality(Quality::auto()))
            ->toUrl();

        $this->thumb = $cloudinary->image($data['public_id'])
            ->namedTransformation(NamedTransformation::name(CloudinaryMediaService::T_THUMB))
            ->delivery(Delivery::format(Format::auto()))
            ->delivery(Delivery::quality(Quality::auto()))
            ->toUrl();

        $this->banner = $cloudinary->image($data['public_id'])
            ->namedTransformation(NamedTransformation::name(CloudinaryMediaService::T_BANNER))
            ->delivery(Delivery::format(Format::auto()))
            ->delivery(Delivery::quality(Quality::auto()))
            ->toUrl();
    }

    public function jsonSerialize(): array
    {
        return [
            'original' => (string) $this->original,
            'photo' => (string) $this->photo,
            'thumb' => (string) $this->thumb,
            'banner' => (string) $this->banner,
            'width' => (int) $this->width,
            'height' => (int) $this->height,
        ];
    }



    /**
     * Used for figuring out the size of the photo after the `main` transformation is applied
     */
    const int MAIN_MAX_H = 2500;
    const int MAIN_MAX_W = 2500;
}
