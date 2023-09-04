<?php

namespace App\Models;

use App\Collections\CustomCollection;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Location
 *
 * @property int $id
 * @property int $site_id
 * @property string $title
 * @property string $title_short
 * @property string|null $street
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zipcode
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CustomCollection|Game[] $games
 * @property-read string $full_address
 * @property-read Collection|Tournament[] $tournaments
 * @method static Builder|Location whereCity($value)
 * @method static Builder|Location whereCreatedAt($value)
 * @method static Builder|Location whereId($value)
 * @method static Builder|Location whereNotes($value)
 * @method static Builder|Location whereSiteId($value)
 * @method static Builder|Location whereState($value)
 * @method static Builder|Location whereStreet($value)
 * @method static Builder|Location whereTitle($value)
 * @method static Builder|Location whereTitleShort($value)
 * @method static Builder|Location whereUpdatedAt($value)
 * @method static Builder|Location whereZipcode($value)
 * @mixin Eloquent
 */
class Location extends Model
{
    use BelongsToTenants;

    /**
     * Specify the tenant columns to use for this model
     * This always ignores the season tenant check
     *
     * @var array
     */
    protected $tenantColumns = ['site_id'];

    /**
     * Gets related games
     *
     * @return HasMany
     */
    public function games(): HasMany
    {
        return $this->hasMany('App\Models\Game');
    }

    /**
     * Gets related tournaments
     *
     * @return HasMany
     */
    public function tournaments(): HasMany
    {
        return $this->hasMany('App\Models\Tournament');
    }

    /**
     * Creates a full_address attribute
     *
     * @return string
     * @noinspection PhpUnused
     */
    public function getFullAddressAttribute(): string
    {
        return $this->street.' '.$this->city.', '.$this->state.' '.$this->zipcode;
    }


    /**
     * Generates an url to a static Google Map image for this location
     *
     * @param int $width
     * @param int $height
     * @param null $zoom
     * @return string URL for a map image
     * @noinspection PhpUnused
     */
    public function googleStaticMap(int $width = 200, int $height = 200, $zoom = null): string
    {

        $url = 'https://maps.googleapis.com/maps/api/staticmap?';
        $url .= 'size=' . $width . 'x' . $height;
        $url .= '&amp;markers=' . urlencode($this->full_address);
        if ($zoom !== null)
            $url .= '&amp;zoom=' . $zoom;
        $url .= '&amp;sensor=false';

        return $url;
    }

    /**
     * Generates a url to Google Maps for this location
     *
     * @return string URL to show this location in Google Maps
     * @noinspection PhpUnused
     */
    public function googleMapLink(): string
    {
        return 'https://maps.google.com/?q=' . urlencode($this->full_address);
    }

    /**
     * Generates a url to directions in Google Maps
     *
     * @return string URL to get directions to this location in Google Maps
     * @noinspection PhpUnused
     */
    public function googleDirectionsLink(): string
    {
        return 'https://maps.google.com/?daddr=' . urlencode($this->full_address);
    }
}
