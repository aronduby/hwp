<?php

namespace App\Models;

use App\Models\Traits\HasTotal;
use Torzer\Awesome\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Recent
 *
 * @property int $id
 * @property int $site_id
 * @property int $season_id
 * @property string $renderer
 * @property string $content
 * @property int $sticky
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read string $rendered
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recent latest($page)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recent total()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recent whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recent whereRenderer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recent whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recent whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recent whereSticky($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Recent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Recent extends Model
{
    use BelongsToTenants, HasTotal;

    /**
     * How long should titles be?
     *
     * @param int
     */
    const TITLE_LIMIT = 30;

    /**
     * The different renderer types, match the renderer field in the db table
     *
     */
    const TYPE_PHOTOS = 'photos';

    const TYPE_ARTICLES = 'articles';

    const TYPE_NOTE = 'note';

    const TYPE_GAME = 'game';

    const TYPE_TOURNAMENT = 'tournament';


    /**
     * The table for Eloquent to use.
     *
     * @var string
     */
    protected $table = 'recent';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['rendered'];

    /**
     * Order the query to get the latest items
     *
     * @param Builder $query
     * @param $page
     * @return Builder
     */
    public function scopeLatest(Builder $query, $page)
    {
        $query->orderBy('created_at', 'desc');

        if ($page === 1) {
            // make sure we have enough items regardless of season
        }

        return $query;
    }

    /**
     * Allows for rendered to be accessed as an attribute
     *
     * @return string
     */
    public function getRenderedAttribute()
    {
        return $this->render();
    }

    /**
     * Shortcut to get and call the renderer
     *
     * @return string
     */
    public function render()
    {
        return $this->getRenderer()->render();
    }

    /**
     * Gets the renderer for this type of recent
     *
     * @return mixed Renderer class
     */
    public function getRenderer()
    {
        $class = '\\App\\Models\\Recent\\Render\\' . ucwords($this->renderer);
        return new $class($this->content, $this);
    }
}
