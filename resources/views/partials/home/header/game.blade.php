{{$game->opponent}} @ @include('partials.location-link', ['location' => $game->location])
<time datetime="@iso($game->start)">
    <span class="day">@day($game->start)</span>
    @time($game->start)
</time>
