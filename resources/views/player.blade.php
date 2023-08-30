@extends('layouts.app')

@section('title')
    {{$player->name}} -
@endsection

@section('content')
    <article class="page--player">

        <header class="page-header bg--dark">
            <div class="bg-elements">
                <div class="bg--gradient"></div>
                <div class="bg--img" style="background-image: url({{$headerPhoto ? $headerPhoto->photo : 'images/ezra-ball.png'}});"></div>
            </div>

            <div class="container">
                <h1>
                    <span class="text--accent">{{$title or '#' . $number}}</span>
                    <span>{{$player->first_name}}</span>
                    <span class="text--muted">{{$player->last_name}}</span>
                </h1>

                <nav class="player-nav sub-nav">
                    <ul>
                        <li class="text--muted">@lang('players.seasons'):</li>
                        @foreach($seasons as $season)
                            <li class="{{$season->season->id == $activeSeasonId ? 'active' : ''}}">
                                <a class="season"
                                   href="@route('players', ['nameKey' => $player->name_key, 'season' => $season->season->id])"
                                   title="@lang('players.viewSeason')"
                                >{{ $season->season->short_title }}</a>
                            </li>
                        @endforeach
                        <li class="{{$activeSeasonId === 0 ? 'active' : ''}}">
                            <a class="all"
                               href="@route('players', ['nameKey' => $player->name_key, 'season' => 0])"
                               title="@lang('players.viewSeason')"
                            >@lang('players.all')</a>
                        </li>
                        @if($player->isShareable())
                            <li class="shareable">
                                <a class="shareable"
                                   href="{!! $player->getShareableUrl() !!}" target="_blank"
                                   data-shareable-type="player"
                                   title="@lang('misc.shareable')"
                               ><i class="fa fa-share-alt-square"></i> @lang('misc.shareable')</a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </header>

        @if($team !== 'STAFF')
            <section class="page-section">
                <div class="bg-elements">
                    <div class="bg--light"></div>
                    <div class="bg--inner-shadow"></div>
                    <div class="bg--grid"></div>
                </div>

                <div class="container">
                    <div class="stats stats--player">
                        @if($position === 'GOALIE')
                            @include('partials.stats.goalie', ['stats' => $stats, 'for' => 'PLAYER'])
                        @else
                            @include('partials.stats.field', ['stats' => $stats, 'for' => 'PLAYER'])
                            @if($player->name_key == 'ParkerMolewyk')
                                @include('partials.stats.thirsts-quenched', ['stats' => $stats, 'for' => 'PLAYER'])
                            @endif
                        @endif
                    </div>
                </div>
            </section>
        @endif

        <script>
            var stats = window.stats || {};
            stats = @json($stats)
        </script>

        @if($badges->count())
            <section class="page-section badges bg--smoke">
                <div class="container">
                    <div class="row center-xs">
                        @each('partials.badge', $badges, 'badge')
                    </div>
                </div>
            </section>
        @endif

        <div class="page-section container">
            <div class="row">
               <div class="col-xs-12 col-md-{{$articles->count() ? '8' : '12'}}">
                   <div class="player-photos full-gallery" data-gallery-path="@route($route, $routeArguments)" >
                       <div class="recap-loader">
                           <div class="loading bg--dark bg--grid-small">
                               <div class="loader"></div>
                               <h1>@lang('misc.loading')</h1>
                           </div>
                       </div>
                   </div>
               </div>

                @if($articles->count())
                    <div class="col-xs-12 col-md-4">
                        <aside class="player-articles card card--no-shadow">
                            <header class="bg--grid bg--primary text-align--left">
                                <h1>@lang('players.articles')</h1>
                            </header>

                            <div class="body player-articles-body">
                                @foreach($articles as $article)
                                    <article>
                                        <h2><a href="{{$article->url}}" title="@lang('players.viewArticle')" target="_blank">{{$article->title}}</a></h2>
                                        <time datetime="@iso($article->published)">@stamp($article->published)</time>
                                        {!! $article->highlight !!}
                                    </article>
                                @endforeach
                            </div>
                        </aside>
                    </div>
                @endif
            </div>
        </div>


    </article>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="{{mix('js/player.js')}}"></script>
    @if($player->name_key == 'NathanPorter')
        <script src="{{mix('js/porter.js')}}"></script>
    @endif
    @if($player->name_key == 'DeclanEdema')
        <script src="{{mix('js/declan.js')}}"></script>
    @endif
@endpush