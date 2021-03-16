@extends('layouts.app')

@section('title')
    @lang('schedule.schedule') -
@endsection

@section('content')
    <article class="page--schedule">

        <header class="page-header header--small">
            <div class="bg-elements">
                <div class="bg--gradient"></div>
                <div class="bg--img"></div>
            </div>
            <div class="container">
                <h1>@lang('schedule.schedule')</h1>
            </div>
        </header>

        @if($upcoming->count())
            <section class="page-section schedule--upcoming">
                <div class="bg-elements">
                    <div class="bg--light"></div>
                    <div class="bg--inner-shadow"></div>
                    <div class="bg--grid"></div>
                </div>
                <div class="container">
                    <header class="divider--bottom text-align--center">
                        <h1><span class="text--muted">@lang('schedule.upcoming')</span> @lang('schedule.events')</h1>
                    </header>

                    <div class="upcoming-wrapper row center-xs">
                        @foreach($upcoming as $date => $events)
                            <section class="col-xs-12 col-md-4">
                                <div class="card upcoming">
                                    <header>
                                        <div class="bg-elements">
                                            <div class="bg--bright"></div>
                                            <div class="bg--grid"></div>
                                        </div>
                                        <h1>@day(new \Carbon\Carbon($date))</h1>
                                    </header>

                                    <table class="body upcoming table table--striped">
                                        <tbody>
                                            @foreach($events as $event)
                                                <tr class="upcoming-event">
                                                    <th class="upcoming-time">@time($event->start)</th>
                                                    <th class="upcoming-team">{{$event->team}}</th>
                                                    <td class="upcoming-opponent">{{$event->opponent}}</td>
                                                    <td class="upcoming-location">
                                                        @if($event->location)@include('partials.location-link', ['location' => $event->location, 'short' => true, 'iconBefore' => true])@endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <div class="page-section container">
            <header class="divider--bottom text-align--center">
                <h1><span class="text--muted">@lang('schedule.full')</span> @lang('schedule.schedule')</h1>
            </header>

            <p class="subscribe-link text-align--center">
                <button
                    title="@lang('schedule.subscribe')"
                    class="subscribe btn btn--text btn--lg"
                >
                    <i class="fa fa-calendar"></i> @lang('schedule.subscribe')
                </button>
            </p>

            <table class="table table--striped table--collapse table--fancyHeader schedule">
                <thead class="bg--grid bg--dark">
                    <tr>
                        <th class="schedule-type">@lang('schedule.type')</th>
                        <th class="schedule-date">@lang('schedule.date')</th>
                        <th class="schedule-team">@lang('schedule.team')</th>
                        <th class="schedule-title">@lang('schedule.title')</th>
                        <th class="schedule-location">@lang('schedule.location')</th>
                        <th class="schedule-result">@lang('schedule.result')</th>
                        <th class="schedule-score">@lang('schedule.score')</th>
                        <th class="schedule-btns"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($full as $event)
                        <?php
                        $class = get_class($event->scheduled);
                        switch($class) {
                            case 'App\Models\Tournament':
                                $type = 'tournament';
                                break;

                            default:
                            case 'App\Models\Game':
                                $type = 'game';
                                if ($event->scheduled->tournament_id) {
                                    $type = 'tournament-game';
                                }
                                break;
                        }
                        ?>
                        <tr
                            data-timestamp="{{$event->start->timestamp}}"
                            class="@if($event->scheduled instanceof App\Models\Game && $event->scheduled->tournament_id)in-tournament @endif"
                        >
                            <td class="schedule-type" data-title="@lang('schedule.type')" data-filter-value="{{$type}}">
                                @if($event->scheduled instanceof App\Models\Game)
                                    <i class="fa fa-square" title="@lang('schedule.game')"></i>&nbsp;<span class="hidden--lg">@lang('schedule.game')</span>
                                @elseif($event->scheduled instanceof App\Models\Tournament)
                                    <i class="fa fa-sitemap fa-rotate-90 fa-lg" title="@lang('schedule.tournament')"></i>&nbsp;<span class="hidden--lg">@lang('schedule.tournament')</span>
                                @endif
                            </td>
                            <td class="schedule-date" data-title="@lang('schedule.date')" data-filter-value="{{$event->start->timestamp}}">
                                @dayWithDateTime($event->start)
                            </td>
                            <td class="schedule-team" data-title="@lang('schedule.team')">
                                {{$event->team}}
                            </td>
                            <td class="schedule-title" data-title="@lang('schedule.title')">
                                {{$event->opponent}}
                            </td>
                            <td class="schedule-location" data-title="@lang('schedule.location')">
                                @if($event->location)
                                    @include('partials.location-link', ['location' => $event->location, 'hideIcon' => true])
                                @endif
                            </td>
                            <td class="schedule-result schedule--{{$event->scheduled->status()}}"
                                data-title="@lang('schedule.result')"
                                @if($event->scheduled instanceof App\Models\Tournament) colspan="2" @endif
                            >
                                {{$event->scheduled->result}}
                            </td>
                            @if($event->scheduled instanceof App\Models\Game)
                                <td class="schedule-score" data-title="@lang('schedule.score')">
                                    @if($event->score_us)
                                        {{$event->score_us}} - {{$event->score_them}}
                                    @endif
                                </td>
                            @endif
                            <td class="schedule-btns action-btns">
                                <div class="btn-group btn-group--end">
                                    @if($event->scheduled instanceof App\Models\Tournament && $event->scheduled->start <= Carbon\Carbon::today())
                                        <a class="btn" href="@route('tournament', ['id'=>$event->scheduled->id])" title="@lang('tournament.games')">
                                            <i class="fa fa-sitemap fa-rotate-90"></i>
                                        </a>
                                    @endif
                                    @if($event->stats_count)
                                        <a class="btn" href="@route($event->type.'.stats', ['id'=>$event->scheduled->id])" title="@lang('misc.stats')">
                                            <i class="fa fa-line-chart"></i>
                                        </a>
                                    @endif
                                    @if($event->album_count)
                                        <a class="btn" href="@route($event->type.'.photos', ['id'=>$event->scheduled->id])" title="@lang('misc.photos')">
                                            <i class="fa fa-picture-o"></i>
                                        </a>
                                    @endif
                                    @if($event->updates_count)
                                        <a class="btn" href="@route($event->type.'.recap', ['id'=>$event->scheduled->id])" title="@lang('misc.recap')">
                                            <i class="fa fa-ticket"></i>
                                        </a>
                                    @endif
                                    @if($event->scheduled instanceof \App\Models\Contracts\Shareable
                                        && $event->scheduled->isShareable())
                                        <a class="btn shareable" data-shareable-type="game" href="{!! $event->scheduled->getShareableUrl() !!}" title="@lang('misc.shareable')"  target="_blank">
                                            <i class="fa fa-share-alt-square"></i>
                                        </a>
                                    @endif
                                    @if(auth()->check() && $event->scheduled instanceof App\Models\Game)
                                        <a class="btn" href="@route('game.stats.edit', ['id'=>$event->scheduled->id])" title="@lang('misc.edit')">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </article>

    <script type="text/html" id="subscribe-modal">
        <article class="note subscribe-modal">
            <header class="bg--dark bg--grid">
                <h1>Subscribe</h1>
            </header>
            <div class="body">
                <p>Choose how you would like to subscribe below</p>
                <ul class="subscribe-providers">
                    <li>
                        <a href="@routeWithProtocol('schedule.subscribe', null, 'webcal')" target="_blank">
                            <i class="fa fa-apple"></i>
                            Apple Calendar
                        </a>
                    </li>
                    <li>
                        <a href="@routeWithProtocol('schedule.subscribe', null, 'webcal')" target="_blank">
                            <i class="fa fa-windows"></i>
                            Outlook
                        </a>
                    </li>
                    <li>
                        <a href="https://www.google.com/calendar/render?cid=@route('schedule.subscribe')" target="_blank">
                            <i class="fa fa-google"></i>
                            Google <em>(online)</em>
                        </a>
                    </li>
                    <li>
                        <a href="https://calendar.live.com/calendar/calendar.aspx?rru=addsubscription&url=@route('schedule.subscribe')&name=@lang('vcal.name')'" target="_blank">
                            <i class="fa fa-windows"></i>
                            Outlook.com <em>(online)</em>
                        </a>
                    </li>
                    <li>
                        <a href="@route('schedule.subscribe')" target="_blank">
                            <i class="fa fa-clipboard"></i>
                            Copy/Paste
                        </a>
                    </li>
                </ul>
            </div>
        </article>
    </script>
@endsection

@push('scripts')
    <script src="{{ elixir('js/schedule.js') }}"></script>
@endpush