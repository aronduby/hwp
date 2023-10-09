<?php
/**
 * Created by PhpStorm.
 * User: Duby
 * Date: 8/18/2016
 * Time: 5:57 PM
 */

namespace App\Http\Controllers;


use App\Models\ActiveSeason;
use App\Models\Game;
use App\Models\Ranking;
use App\Models\Schedule;
use App\Services\MediaServices\MediaService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;

class HomeController extends Controller
{

    /**
     * The injected media service to use
     *
     * @var MediaService
     */
    protected $mediaService;

    /**
     * The currently active season
     *
     * @var ActiveSeason
     */
    protected $season;

    /**
     * @param MediaService $mediaService
     * @param ActiveSeason $season
     */
    public function __construct(MediaService $mediaService, ActiveSeason $season)
    {
        $this->mediaService = $mediaService;
        $this->season = $season;
    }

    /**
     * Handle the entire homepage. Mostly just calls other protected functions
     *
     * @return Factory|View
     * @throws \Exception
     * @throws \Throwable
     */
    public function index()
    {
        $header = $this->header()->render();
        $results = $this->latestResults()->render();
        $notifications = $this->notifications()->render();
        $badges = $this->badges()->render();
        $content = $this->content()->render();
        
        return view('home', compact('header', 'results', 'notifications','badges', 'content'));
    }

    /**
     * Render the header section
     *
     * @return Factory|View
     */
    public function header()
    {
        // TODO - add site name?
        $photo = $this->mediaService->forHome();
        $ranking = $this->season->ranking;
        $rankingTitle = $this->season->ranking_title;
        $varsity = Game::team('v')->upcoming()->first();
        $jv = Game::team('jv')->upcoming()->first();
        $upcoming = isset($varsity) || isset($jv);

        return view('partials.home.header', compact('photo', 'ranking', 'rankingTitle', 'upcoming', 'varsity', 'jv'));
    }

    /**
     * Render the latest results section
     *
     * @return Factory|View
     */
    public function latestResults()
    {
        $results = Game::with('location')
            ->withCount(['album', 'stats', 'updates'])
            ->results()
            ->take(4)
            ->get();

        return view('partials.home.results', compact('results'));
    }

    /**
     * Render the badges section
     *
     * @return Factory|View
     */
    public function badges()
    {
        $badges = $this->season->badges;

        return view('partials.home.badges', compact('badges'));
    }

    /**
     * Renders the notifications section
     *
     * @return Factory|View
     */
    public function notifications()
    {
        return view('partials.home.notifications', []);
    }

    /**
     * Render the first page of the content
     *
     * @return Factory|View
     */
    public function content()
    {
        $upcoming = Schedule::with('location')
            ->upcoming()
            ->take(10)
            ->get()
            ->groupByDate('start', 'Y-m-d');
        $rankings = $this->getRankings();

        return view('partials.home.content-grid', compact('upcoming', 'rankings'));
    }

    /**
     * Handles the calls for paginating the recent content
     *
     * @return Factory|View
     */
    public function recent()
    {
        $recent = new \App\Models\Recent\Paginator();
        return response()->json($recent->toArray());
    }

    /**
     * Handles the calls for paginating the rankings
     *
     * @return Factory|View
     */
    public function rankings()
    {
        return response()->json($this->getRankings()->toArray());
    }

    /**
     * Gets the paginator for the rankings
     *
     * @return Paginator
     */
    protected function getRankings()
    {
        $rankings = Ranking::simplePaginate(1);
        $rankings->setPath(route('rankings'));

        return $rankings;
    }

}