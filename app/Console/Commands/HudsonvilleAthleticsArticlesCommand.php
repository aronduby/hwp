<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\HasOGPhoto;

class HudsonvilleAthleticsArticlesCommand extends ArticleImporter
{
    use HasOGPhoto;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parsers:articles:hudsonvilleathletics  {instanceId : The ID of the JobInstance to use }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses articles from the hudsonville athletics website';

    /**
     * @var string
     */
    protected $url = "http://feeds.feedburner.com/hudsonvilleathletics?format=xml";

    /**
     * Gets the last ran timestamp and does all the hard work
     *
     * @param int $lastRan
     * @return array [[article ids], # of tags]
     */
    protected function parse(int $lastRan): array
    {
        $this->logDebug('Last Ran', [$lastRan]);
        $this->info('Importing from ' . $this->url);

        $feed = new \SimpleXMLElement($this->url, null, true);
        $imported_articles = [];
        $imported_tags = 0;
        foreach($feed->channel->item as $item){

            // if the pubdate is less than our last parse just continue on
            if($lastRan > strtotime((string)$item->pubDate)) {
                $this->logDebug('Skipping article, to old', [
                    'title' => (string) $item->title,
                    'pubDate' => (string) $item->pubDate,
                    'pubDateTS' => strtotime((string)$item->pubdate),
                ]);
                continue;
                
            } else {
                // search the article for players
                $found_players = [];
                $content = strip_tags(html_entity_decode((string)$item->children('http://purl.org/rss/1.0/modules/content/')->encoded));
                $this->playerlist->each(function($playerSeason) use (&$found_players, $content) {
                    $found_pos = stripos($content, $playerSeason->player->name);
                    if($found_pos !== false){
                        $found_players[$playerSeason->player->id] = $this->excerptAndHighlight(strip_tags($content), $playerSeason->player->name);
                    }
                });

                if(count($found_players)>0){
                    $published = strtotime((string)$item->pubDate);
                    $article = [
                        'title' => (string)$item->title,
                        'url' => (string)$item->link,
                        'photo' => $this->getPhoto((string)$item->link),
                        'description' => trim(strip_tags(html_entity_decode((string)$item->description))),
                        'published' => date('Y-m-d G:i:s', $published),
                        'created_at' => date('Y-m-d G:i:s', $published),
                        'updated_at' => date('Y-m-d G:i:s', $published),
                    ];
                    $this->logDebug('importing', compact('article', 'found_players'));

                    $this->articleInsertStmt->execute($article);
                    $article_id = $this->pdo->lastInsertId();
                    $imported_articles[] = $article_id;

                    foreach($found_players as $player_id => $highlight){
                        $pta = [
                            'article_id' => $article_id,
                            'player_id' => $player_id,
                            'highlight' => $highlight,
                            'created_at' => date('Y-m-d G:i:s', $published),
                            'updated_at' => date('Y-m-d G:i:s', $published)
                        ];
                        $this->playerToArticleInsertStmt->execute($pta);
                        $imported_tags++;
                    }

                } else {
                    $this->logDebug('skipping article, no players found', ['title' => (string)$item->title]);
                    continue;
                }
            }
        }

        return [$imported_articles, $imported_tags];
    }
}
