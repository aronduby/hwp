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
use App\Models\Photo;
use App\Models\Ranking;
use App\Models\Recent;
use App\Models\Schedule;
use Illuminate\Pagination\Paginator;

class HomeController extends Controller
{

    /**
     * The currently active season
     *
     * @var ActiveSeason
     */
    protected $season;

    /**
     * Handle the entire homepage. Mostly just calls other protected functions
     *
     * @param ActiveSeason $season
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     * @throws \Throwable
     */
    public function index(ActiveSeason $season)
    {
        $this->season = $season;

        $header = $this->header()->render();
        $results = $this->latestResults()->render();
        $badges = $this->badges()->render();
        $content = $this->content()->render();
        
        return view('home', compact('header', 'results', 'badges', 'content'));
    }

    /**
     * Render the header section
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function header()
    {
        // TODO - add site name?
        // $photos = Photo::inRandomOrder()->take(5)->get();
        $photo = Photo::inRandomOrder()->first();
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function badges()
    {
        $badges = $this->season->badges;

        return view('partials.home.badges', compact('badges'));
    }


    /**
     * Render the first page of the content
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function recent()
    {
        $recent = new \App\Models\Recent\Paginator();
        return response()->json($recent->toArray());
    }

    /**
     * Handles the calls for paginating the rankings
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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