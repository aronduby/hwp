<?php

namespace App\Listeners;

use App\Events\ArticleImported as ArticleImportedEvent;
use App\Models\Article;
use App\Notifications\ArticleImported as ArticleImportedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticleImportedNotifier implements ShouldQueue
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
     * Handle the event.
     *
     * @param  ArticleImportedEvent $event
     * @return void
     */
    public function handle(ArticleImportedEvent $event)
    {
        /**
         * @var Article $article
         */
        $article = Article::findOrFail($event->articleId);
        $notification = new ArticleImportedNotification($article);
        $site = $event->site;
        $site->notify($notification);
    }
}
