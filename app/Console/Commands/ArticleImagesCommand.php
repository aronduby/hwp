<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Fusonic\OpenGraph\Consumer;

class ArticleImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parsers:articles:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attempts to get images for articles that don\'t have any';

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
     * @return mixed
     */
    public function handle()
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

    public function parse($url)
    {
        $consumer = new Consumer();
        try {
            return $consumer->loadUrl($url);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return new \StdClass();
        }
    }
}
