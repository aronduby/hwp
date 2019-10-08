<?php

namespace App\Models;

use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

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
