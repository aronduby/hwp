<?php

namespace App\Console\Commands;

use App\Events\ArticleImported;
use App\Models\Article;
use Illuminate\Console\Command;

class ManualArticleImportCommand extends LoggedCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:manual-article-import {articleId : The ID of the article to handle}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Triggers the article import events for an article that was manually added';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Landlord::disable();

        $articleId = $this->argument('articleId');
        $article = Article::with(['site', 'season'])->findOrFail($articleId);

        $event = new ArticleImported($article->site, $article->season, $articleId);
        event($event);

        \Landlord::enable();
    }
}
