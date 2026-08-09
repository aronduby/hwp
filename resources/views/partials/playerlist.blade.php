@inject('playerList', 'App\Services\PlayerListService')

<section class="player-list">
    @foreach(['V', 'JV', 'STAFF'] as $team)
        @if($playerList->team($team))
        <section class="team team--{{$team}}">
            <header><h4>@lang('misc.'.$team)</h4></header>
            <ul>
                @foreach($playerList->team($team) as $playerSeason)
                    <li>
                        <a href="@route('players', ['player' => $playerSeason->player])">
                            <span class="player--number">{{$playerSeason->getNumber($team)}}</span>
                            <span class="player--name">{{$playerSeason->name}}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
        @endif
    @endforeach
</section>
