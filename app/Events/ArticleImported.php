<?php

namespace App\Events;

use App\Events\Contracts\Recent as RecentEvent;
use App\Models\Recent as Recent;
use App\Models\Season;
use App\Models\Site;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class ArticleImported implements RecentEvent, ShouldQueue
{
    use SerializesModels;

    /**
     * @var Site
     */
    public $site;

    /**
     * @var Season
     */
    public $season;

    /**
     * @var int
     */
    public $articleId;

    /**
     * Create a new event
     *
     * @param Site $site
     * @param Season $season
     * @param array $articleId
     *
     * @return void
     */
    public function __construct(Site $site, Season $season, array $articleId)
    {
        $this->site = $site;
        $this->season = $season;
        $this->articleId = $articleId;
    }

    /**
     * Get the value for site_id
     *
     * @return integer
     */
    public function getSiteId(): int
    {
        return $this->site->id;
    }

    /**
     * Get the value for season_id
     *
     * @return integer
     */
    public function getSeasonId(): int
    {
        return $this->season->id;
    }

    /**
     * Get the value for renderer
     *
     * @return string
     */
    public function getRenderer(): string
    {
        return Recent::TYPE_ARTICLES;
    }

    /**
     * Get the value for content
     *
     * @return string
     */
    public function getContent(): string
    {
        return json_encode([$this->articleId]);
    }

    /**
     * Get the value for sticky
     *
     * @return boolean
     */
    public function getSticky(): bool
    {
        return false;
    }
}
