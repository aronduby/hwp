<?php /** @noinspection PhpUnused */

namespace App\Models;

use App\Models\Recent\Render\Renderer;
use App\Models\Traits\HasTotal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use NunoMazer\Samehouse\BelongsToTenants;

/**
 * App\Models\Recent
 *
 * @property int $id
 * @property int $site_id
 * @property int $season_id
 * @property string $renderer
 * @property string $content
 * @property int $sticky
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $rendered
 * @method static Builder|Recent latest($page)
 * @method static Builder|Recent total()
 * @method static Builder|Recent whereContent($value)
 * @method static Builder|Recent whereCreatedAt($value)
 * @method static Builder|Recent whereId($value)
 * @method static Builder|Recent whereRenderer($value)
 * @method static Builder|Recent whereSeasonId($value)
 * @method static Builder|Recent whereSiteId($value)
 * @method static Builder|Recent whereSticky($value)
 * @method static Builder|Recent whereUpdatedAt($value)
 */
#[Table('recent')]
#[Appends('rendered')]
class Recent extends Model
{
    use BelongsToTenants, HasTotal;

    /**
     * How long should titles be?
     */
    const int TITLE_LIMIT = 30;

    /**
     * The different renderer types, match the renderer field in the db table
     *
     */
    const string TYPE_PHOTOS = 'photos';

    const string TYPE_ARTICLES = 'articles';

    const string TYPE_NOTE = 'note';

    const string TYPE_GAME = 'game';

    const string TYPE_TOURNAMENT = 'tournament';

    /**
     * Order the query to get the latest items
     *
     * @param Builder $query
     * @param int $page
     * @return void
     */
    #[Scope]
    protected function latest(Builder $query, int $page): void
    {
        $query
            ->orderBy('sticky', 'desc')
            ->orderBy('created_at', 'desc');

        /** @noinspection PhpStatementHasEmptyBodyInspection */
        if ($page === 1) {
            // TODO -- make sure we have enough items regardless of season?
        }
    }

    /**
     * Allows for rendered to be accessed as an attribute     *
     */
    protected function rendered(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->render()
        );
    }

    /**
     * Shortcut to get and call the renderer
     *
     * @return string
     */
    public function render(): string
    {
        return $this->getRenderer()->render();
    }

    /**
     * Gets the renderer for this type of recent
     *
     * @return Renderer Renderer class
     */
    public function getRenderer(): Renderer
    {
        $class = '\\App\\Models\\Recent\\Render\\' . ucwords($this->renderer);
        return new $class($this->content, $this);
    }
}
