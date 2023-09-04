<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Easy way for dependency inject to get the site being viewed
 * 
 * Class ActiveSite
 *
 * @package App\Models
 * @property int $id
 * @property bool $is_picker
 * @property int|null $parent_id
 * @property string $domain
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Photo[] $featuredPhotos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\JobInstance[] $jobs
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\Site|null $picker
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Season[] $seasons
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Site[] $sites
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Site domain($domain)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActiveSite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActiveSite whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActiveSite whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActiveSite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActiveSite whereIsPicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActiveSite whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActiveSite whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActiveSite whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActiveSite whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ActiveSite extends Site
{
    protected $table = 'sites';

    /**
     * This is used with polymorphic relationships to tell what class name should be used with the relationships
     * Normally this comes from a morphMap or just the calling classes name, but since we want every Site/ActiveSite
     * to share this relationship properly we overload it here to always be the site class
     *
     * @return string
     */
    public function getMorphClass()
    {
        return Site::class;
    }


}
