<?php

namespace App\Listeners;

use App\Events\Contracts\Recent as RecentEvent;
use App\Models\Recent;

class RecentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Create the entry in the recent table
     *
     * @param  RecentEvent  $event
     * @return void
     */
    public function handle(RecentEvent $event): void
    {
        $recent = new Recent();
        $recent->site_id = $event->getSiteId();
        $recent->season_id = $event->getSeasonId();
        $recent->renderer = $event->getRenderer();
        $recent->content = $event->getContent();
        $recent->sticky = $event->getSticky();

        $recent->save();
    }
}
