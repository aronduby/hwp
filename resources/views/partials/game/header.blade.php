<header class="page-header">
    <div class="bg-elements">
        <div class="bg--gradient"></div>
        <div class="bg--img" @if($headerPhoto)style="background-image: url({{$headerPhoto}});"@endif></div>
    </div>

    <div class="container">
        <h1>
            <span>@lang('misc.'.$game->team)</span>
            <small>@lang('misc.vs')</small>
            <span class="text--muted">{{$game->opponent}}</span>
        </h1>

        <nav class="game-nav sub-nav">
            <ul>
                @if($game->tournament_id)
                    <li>
                        <a href="@route('tournament', ['tournament' => $game->tournament_id])"
                           title="@lang('schedule.tournament')"
                        ><i class="fa fa-sitemap fa-rotate-90"></i> @lang('schedule.tournament')</a>
                    </li>
                @endif
                @if($game->stats_count)
                    <li class="@active('game.stats')">
                        <a href="@route('game.stats', ['game' => $game->id])"
                           title="@lang('game.stats')"
                        ><i class="fa fa-line-chart"></i> @lang('game.stats')</a>
                    </li>
                @endif
                @if($game->album_count)
                    <li class="@active('game.photos')">
                        <a href="@route('game.photos', ['game' => $game->id])"
                           title="@lang('game.photos')"
                        ><i class="fa fa-picture-o"></i> @lang('game.photos')</a>
                    </li>
                @endif
                @if($game->updates_count)
                    <li class="@active('game.recap')">
                        <a href="@route('game.recap', ['game' => $game->id])"
                           title="@lang('game.recap')"
                        ><i class="fa fa-ticket"></i> @lang('game.recap')</a>
                    </li>
                @endif
                @if($game->isShareable())
                    <li>
                        <a class="shareable" data-shareable-type="game" href="{!! $game->getShareableUrl() !!}"
                           title="@lang('misc.shareable')" target="_blank"
                        ><i class="fa fa-share-alt-square"></i> @lang('misc.shareable')</a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</header>