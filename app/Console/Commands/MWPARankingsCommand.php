<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\HasJobInstance;
use App\Models\JobInstance;
use App\Models\JobLog;
use App\Models\Rank;
use App\Models\Ranking;
use App\Models\Season;
use App\Models\Site;
use App\Notifications\RankingsUpdated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Goutte\Client as GoutteClient;
use Symfony\Component\DomCrawler\Crawler;

class MWPARankingsCommand extends LoggedCommand
{
    use HasJobInstance;

    /**
     * How long to cache the parsed rankings, in minutes
     */
    const CACHE_FOR = 50;

    /**
     * The prefix for the key in the cache
     */
    const CACHE_KEY_PREFIX = 'mwpa.rankings.';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parsers:mwpa:rankings {instanceId : The ID of the JobInstance to use }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses weekly rankings for teams from the MWPA website';

    /**
     * The base url to query
     *
     * @var string
     */
    // protected $urlBase = 'http://michiganwaterpolo.com/both/rankings/index.php';
    protected $urlBase = 'http://site.swimscoring.local/fake-rankings.html';

    /**
     * @var Site
     */
    protected $site;

    /**
     * @var Season
     */
    protected $season;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Landlord::disable();

        $jobInstance = $this->getJobInstance();
        $jobLog = $this->getJobLog();

        try {

            $site = $jobInstance->site;
            /**
             * @var Season $season
             */
            $season = $site->seasons()->where('current', '=', true)->firstOrFail();

            $this->site = $site;
            $this->season = $season;

            $gender = $jobInstance->settings['gender'];
            $week = intval($jobInstance->settings['week']);
            $teamName = $jobInstance->settings['name'];

            $this->info('getting rankings');
            $rankings = $this->getRankings(compact('gender', 'week'));

            // if there's no rankings yet, stop processing
            if ($rankings === false) {
                $this->info('no rankings yet');
                return;
            }

            $findSelf = function($item) use ($teamName) {
                return $item->team === $teamName;
            };

            // look for the current team
            $newRank = $rankings->ranks->first($findSelf);
            if ($newRank) {
                $newRank->self = true;
            }

            $lastRank = Ranking::latest()->first()
                ->ranks
                ->first($findSelf);

            $this->info('saving rankings');
            $rankings->save();
            $rankings->ranks()->saveMany($rankings->ranks);

            // update the season ranking data
            $this->info('updating season');
            $season->ranking = $newRank ? $newRank->rank : null;
            $season->ranking_updated = Carbon::now();
            $season->save();

            // if the team is ranked, send the notification
            if ($newRank || $lastRank) {
                $this->info('sending rank notification');
                $notification = new RankingsUpdated($newRank, $lastRank);
                $site->notify($notification);
            }

            // increment the settings
            $this->info('updating the settings');
            $jobInstance->settings = array_merge($jobInstance->settings, ['week' => ++$week]);
            $jobInstance->last_ran = now();

            $jobLog->state = JobLog::SUCCESS;

        } catch (\Throwable $exception) {
            $this->error('Caught exception: ' . $exception->getMessage());
            $jobLog->state = JobLog::ERROR;
        }

        $this->info('saving instance and log');
        $jobInstance->save();
        $jobInstance->logs()->save($jobLog);

        \Landlord::enable();
    }

    /**
     * Get's the ranking for the given query parameters
     * This will either get it from the cache, or load/parse the MWPA for the data, then cache it
     *
     * @param $query
     * @return Ranking
     */
    private function getRankings($query): Ranking
    {
        $queryString = http_build_query($query);

        return Cache::remember(self::CACHE_KEY_PREFIX.$queryString, self::CACHE_FOR, function() use ($queryString) {
            return $this->parse($queryString);
        });
    }

    /**
     * TODO -- this all has to change for their new site, hopefully it will be nicer
     *
     * Parse the data from the MWPA site and return the Ranking
     *
     * @param $queryString
     * @return Ranking|bool
     */
    private function parse($queryString)
    {
        $siteId = $this->site->id;
        $seasonId = $this->season->id;

        $url = $this->urlBase . '?' . $queryString;
        $guzzle = $this->guzzleClient();
        $goutte = new GoutteClient();
        $goutte->setClient($guzzle);

        $this->info('fetching fresh rankings: '. $url);
        $crawler = $goutte->request('GET', $url);

        $rows = $crawler->filterXPath('//table[@class="rankings"]/tr');
        // only header row and empty notice, no rankings out yet
        if (count($rows) === 2) {
            return false;
        }

        $ranking = new Ranking();
        $ranking->site_id = $siteId;
        $ranking->season_id = $seasonId;

        // parse the rows for the ranks
        // skip the heading row
        $len = count($rows);
        for($i = 1; $i < $len; $i++) {
            $row = $rows->eq($i);
            $cells = $row->children();
            $rank = (int) trim($cells->eq(0)->text());
            $team = trim($cells->eq(1)->text());
            $tied = str_contains($team, '(Tie)');

            if ($tied) {
                $team = trim(str_replace('(Tie)', '', $team));
            }

            $rank = new Rank(compact('rank', 'team', 'tied'));
            $rank->site_id = $siteId;
            $rank->season_id = $seasonId;

            $ranking->ranks[] = $rank;
        }
        
        // parse the heading for the date range
        $heading = $crawler->filter('h2')
            ->reduce(function(Crawler $node) {
               return starts_with($node->text(), 'MWPA Boys Rankings');
            })->text();

        preg_match('/MWPA Boys Rankings - Week (?P<week>\d+) - \((?P<start>\w+ \d{1,2}) - (?P<end>\w+ \d{1,2})\)/', $heading, $matched);
        if(count($matched) > 1) {
            $ranking->week = (int)$matched['week'];
            $ranking->start = Carbon::parse($matched['start']);
            $ranking->end = Carbon::parse($matched['end']);
        }

        return $ranking;
    }


}
