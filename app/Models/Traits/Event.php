<?php
/**
 * Created by PhpStorm.
 * User: Duby
 * Date: 8/19/2016
 * Time: 12:42 AM
 */

namespace App\Models\Traits;


use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait Event
{

    /**
     * Scope to entities where end >= today
     * Using end to account for tournaments
     *
     * @param Builder $query
     * @return Builder $query
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        $today = Carbon::today();
        return $query->where('end', '>=', $today)
            ->orderBy('start', 'asc');
    }

    /**
     * Scopes to entities with the provided team
     *
     * @param Builder $query
     * @param string $team
     * @return Builder $query
     */
    public function scopeTeam(Builder $query, string $team): Builder
    {
        return $query->where('team', '=', strtoupper($team));
    }

    /**
     * Scope to entities where the score is not null, latest first
     *
     * @param Builder $query
     * @return Builder $query
     */
    public function scopeResults(Builder $query): Builder
    {
        return $query->whereNotNull('score_us')
            ->orderBy('start', 'desc');
    }

    /**
     * Get the win/loss/tie status of the event, or false
     *
     * @return bool|string
     */
    public function status(): bool|string
    {
        if ($this->score_us > $this->score_them) {
            return Game::WIN;

        } elseif($this->score_us < $this->score_them) {
            return Game::LOSS;

        } elseif($this->score_us !== null && $this->score_us === $this->score_them) {
            return Game::TIE;

        } else {
            return false;
        }
    }
}
