<?php /** @noinspection PhpUnused */

namespace App\Console\Commands;

use App\Events\ArticleImported;
use App\Models\Article;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use NunoMazer\Samehouse\Facades\Landlord;

#[Signature('events:manual-article-import {articleId : The ID of the article to handle}')]
#[Description('Triggers the article import events for an article that was manually added')]
class ManualArticleImportCommand extends LoggedCommand
{
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        Landlord::disable();

        $articleId = $this->argument('articleId');
        $article = Article::with(['site', 'season'])->findOrFail($articleId);

        $event = new ArticleImported($article->site, $article->season, $articleId);
        event($event);

        Landlord::enable();
    }
}
