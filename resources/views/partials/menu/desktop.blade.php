@inject('activeSeason', 'App\Models\ActiveSeason')
@inject('site', 'App\Models\ActiveSite')

<nav id="desktop-menu">
    <ul>
        <li class="players has-subs subs--super">
            <a href="@route('playerlist')" title="">@lang('menu.players')</a>
            @include('partials.playerlist')
        </li>
        <li class="schedule">
            <a href="@route('schedule')" title="@lang('menu.schedule')">@lang('menu.schedule')</a>
        </li>
        {{--<li class="scoring">--}}
        {{--<a href="@route('scoring')" title="@lang('menu.scoring')">@lang('menu.scoring')</a>--}}
        {{--</li>--}}
        <li class="photos">
            <a href="@route('albumlist')" title="@lang('menu.photos')">@lang('menu.photos')</a>
        </li>
        <li class="stats">
            <a href="@route('stats')" title="@lang('menu.stats')">@lang('menu.stats')</a>
        </li>
        <li class="parents">
            <a href="@route('parents')" title="@lang('menu.parents')">@lang('menu.parents')</a>
        </li>
        @if($site->seasons->count())
        <li class="seasons has-subs subs--right {{!$activeSeason->current ? 'seasons--not-current' : ''}}">
            <label>@warn(!$activeSeason->current, menu.notViewingCurrentSeason) @lang('menu.season'):<span>{{$activeSeason->title}}</span></label>
            <section>
                <ul>
                    @foreach($site->seasons->reverse() as $season)
                        <li class="{{$season->id == $activeSeason->id ? 'season--active' : ''}} {{$season->current ? 'season--current' : ''}}">
                            <a href="#" data-season-id="{{$season->id}}" data-current="{{$season->current ? true : false}}" @if($season->current)title="@lang('menu.currentSeason')"@endif>
                                @if($season->current)<i class="current-indicator fa fa-dot-circle-o text--accent" title="@lang('menu.currentSeason')"></i>@endif{{$season->title}}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        </li>
        @endif
    </ul>
</nav>