<?php

namespace App\Models;

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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Collections\CustomCollection|\App\Models\Game[] $games
 * @property-read string $full_address
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tournament[] $tournaments
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereTitleShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereZipcode($value)
 * @mixin \Eloquent
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function games()
    {
        return $this->hasMany('App\Models\Game');
    }

    /**
     * Gets related tournments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournaments()
    {
        return $this->hasMany('App\Models\Tournament');
    }

    /**
     * Creates a full_address attribute
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        return $this->street.' '.$this->city.', '.$this->state.' '.$this->zipcode;
    }


    /**
     * Generates a url to a static Google Map image for this location
     *
     * @param int $width
     * @param int $height
     * @param null $zoom
     * @return string URL for a map image
     */
    public function googleStaticMap($width = 200, $height = 200, $zoom = null)
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
     */
    public function googleMapLink()
    {
        return 'https://maps.google.com/?q=' . urlencode($this->full_address);
    }

    /**
     * Generates a url to directions in Google Maps
     *
     * @return string URL to get directions to this location in Google Maps
     */
    public function googleDirectionsLink()
    {
        return 'https://maps.google.com/?daddr=' . urlencode($this->full_address);
    }
}
