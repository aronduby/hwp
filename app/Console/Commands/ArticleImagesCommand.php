<?php /** @noinspection PhpUnused */

namespace App\Console\Commands;

use App\Models\Article;
use Exception;
use Fusonic\OpenGraph\Objects\ObjectBase;
use Fusonic\OpenGraph\Objects\Website;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Fusonic\OpenGraph\Consumer;
use StdClass;

#[Signature('parsers:articles:images')]
#[Description("Attempts to get images for articles that don't have any")]
class ArticleImagesCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $articles = Article::allTenants()->whereNull('photo')->get();
        $articles->each(function($article) {
            $data = $this->parse($article->url);
            if (property_exists($data, 'images')
                && count($data->images)
                && $data->images[0]->url !== 'None'
            ) {
                $article->photo = $data->images[0]->url;
            } else {
                $article->photo = '';
            }

            $article->save();
        });
    }

    public function parse($url): Website|ObjectBase|StdClass
    {
        $consumer = new Consumer();
        try {
            return $consumer->loadUrl($url);
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return new StdClass();
        }
    }
}
