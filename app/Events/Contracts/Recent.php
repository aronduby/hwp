<?php

namespace App\Events\Contracts;

interface Recent
{
    /**
     * Get the value for site_id
     *
     * @return integer
     */
    public function getSiteId(): int;

    /**
     * Get the value for season_id
     *
     * @return integer
     */
    public function getSeasonId(): int;

    /**
     * Get the value for renderer
     *
     * @return string
     */
    public function getRenderer(): string;

    /**
     * Get the value for content
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Get the value for sticky
     *
     * @return boolean
     */
    public function getSticky(): bool;

}
