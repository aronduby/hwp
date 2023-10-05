@inject('playerList', 'App\Services\PlayerListService')
@inject('activeSeason', 'App\Models\ActiveSeason')
@inject('site', 'App\Models\ActiveSite')

{{-- http://tympanus.net/codrops/2013/08/13/multi-level-push-menu/ --}}
<nav id="mp-menu" class="mp-menu">
    <div class="mp-level">
        <header>
            <h2>@lang('menu.mainMenu')</h2>
        </header>
        <div class="icon"><i class="fa fa-home"></i></div>
        <ul>
            <li class="@if($site->picker) split @endif">
                @if ($site->picker)
                    <a href="//{{$site->picker->domain}}.{{app('App\Http\Requests\Request')->getTLD()}}" class="team-picker" title="team picker"><i class="fa fa-th-large"></i></a>
                @endif
                <a href="@route('home')">@lang('menu.home')</a>
            </li>

            <li class="mp-has-subs">
                <a href="@route('playerlist')"><i class="fa-solid fa-angle-left"></i>@lang('menu.players')</a>
                <div class="mp-level">
                    <header>
                        <h2>@lang('menu.players')</h2>
                    </header>
                    <div class="icon"><i class="fa fa-rebel"></i></div>
                    <a class="mp-back" href="#"><i class="fa-solid fa-angle-right"></i>back</a>
                    <ul>
                        @foreach(['V', 'JV', 'STAFF'] as $team)
                            @if($playerList->team($team))
                            <li class="mp-has-subs">
                                <a href="#"><i class="fa-solid fa-angle-left"></i>@lang('misc.'.$team)</a>
                                <div class="mp-level">
                                    <header>
                                        <h2>@lang('misc.'.$team)</h2>
                                    </header>
                                    <a class="mp-back" href="#"><i class="fa-solid fa-angle-right"></i>back</a>
                                    <ul class="team team--{{$team}}">
                                        @foreach($playerList->team($team) as $player)
                                            <li>
                                                <a href="@route('players', ['nameKey' => $player->nameKey])">
                                                    <span class="player--number">{{$player->number}}</span>
                                                    <span class="player--name">{{$player->name}}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </li>

            <li>
                <a href="@route('schedule')">@lang('menu.schedule')</a>
            </li>

            <li>
                <a href="@route('albumlist')">@lang('menu.photos')</a>
            </li>

            <li>
                <a href="@route('stats')" title="@lang('menu.stats')">@lang('menu.stats')</a>
            </li>

            <li>
                <a href="@route('files')" title="@lang('menu.files')">@lang('menu.files')</a>
            </li>

            <li class="mp-has-subs seasons">
                <a href="#"><i class="fa-solid fa-angle-left"></i>@lang('menu.seasons')@warn(!$activeSeason->current, menu.notViewingCurrentSeason)</a>
                <div class="mp-level">
                    <header>
                        <h2>@lang('menu.seasons')</h2>
                    </header>
                    <a class="mp-back" href="#"><i class="fa-solid fa-angle-right"></i>back</a>
                    <ul>
                        @foreach($site->seasons->reverse() as $season)
                            <li class="{{$season->id == $activeSeason->id ? 'season--active' : ''}} {{$season->current ? 'season--current' : ''}}">
                                <a href="#" data-season-id="{{$season->id}}" data-current="{{$season->current ? true : false}}" @if($season->current)title="@lang('menu.currentSeason')"@endif>
                                    @if($season->current)<i class="current-indicator fa fa-dot-circle-o text--accent" title="@lang('menu.currentSeason')"></i>@endif{{$season->title}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>