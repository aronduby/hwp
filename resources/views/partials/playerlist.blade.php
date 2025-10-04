@inject('playerList', 'App\Services\PlayerListService')

<section class="player-list">
    @foreach(['V', 'JV', 'STAFF'] as $team)
        @if($playerList->team($team))
        <section class="team team--{{$team}}">
            <header><h4>@lang('misc.'.$team)</h4></header>
            <ul>
                @foreach($playerList->team($team) as $player)
                    <li>
                        <a href="@route('players', ['nameKey' => $player->nameKey])">
                            <span class="player--number">{{$player->getNumber($team)}}</span>
                            <span class="player--name">{{$player->name}}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
        @endif
    @endforeach
</section>