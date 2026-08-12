<div class="recent recent--game" data-recent-id="{{$recent->id}}">
    <div class="bg-elements">
        <div class="bg--dark-gray"></div>
        <div class="bg--img" @if($bg)style="background-image:url({{$bg}}"@endif()></div>
        <div class="bg--grid blend--overlay"></div>
        <div class="bg--bottom-third-primary"></div>
    </div>

    <a href="@route($route, ['game' => $game->id])" title="@lang('recent.viewGame')">
        <div class="tag"><span><em>@lang('recent.game')</em></span></div>

        <h1>@lang('misc.'.$game->team) <span class="text--{{$game->status()}}">@lang('recent.game-'.$game->status())</span> {{$game->opponent}} {{$game->score_us}} to {{$game->score_them}}</h1>

        <time datetime="@iso($recent->created_at)">@stamp($recent->created_at)</time>
    </a>

    <div class="recent--hover">
        <h1>@lang('recent.viewGame')</h1>
    </div>
</div>
