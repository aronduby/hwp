<?php

namespace App\Models;


use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Photo[] $featuredPhotos
 * @property-read Collection|JobInstance[] $jobs
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read Site|null $picker
 * @property-read Collection|Season[] $seasons
 * @property-read Collection|Site[] $sites
 * @property-read Settings $settings
 * @method static Builder|Site domain($domain)
 * @method static Builder|ActiveSite whereCreatedAt($value)
 * @method static Builder|ActiveSite whereDescription($value)
 * @method static Builder|ActiveSite whereDomain($value)
 * @method static Builder|ActiveSite whereId($value)
 * @method static Builder|ActiveSite whereIsPicker($value)
 * @method static Builder|ActiveSite whereParentId($value)
 * @method static Builder|ActiveSite whereSubtitle($value)
 * @method static Builder|ActiveSite whereTitle($value)
 * @method static Builder|ActiveSite whereUpdatedAt($value)
 * @mixin Eloquent
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
    public function getMorphClass(): string
    {
        return Site::class;
    }


}
