@extends('layouts.app')

@section('title')
    @lang('stats.stats') -
@endsection

@section('content')
    <article class="page--stats loading @if(count($data['stats']) === 0) empty @endif">

        <header class="page-header header--small">
            <div class="bg-elements">
                <div class="bg--gradient"></div>
                <div class="bg--img"></div>
            </div>
            <div class="container">
                <h1><span class="text--muted">@lang('stats.aggregate')</span> @lang('stats.stats')</h1>
            </div>
        </header>

        <section class="page-section section--dateRange">
            <div class="bg-elements">
                <div class="bg--light"></div>
                <div class="bg--inner-shadow"></div>
                <div class="bg--grid"></div>
            </div>

            <section class="container text-align--center">
                <header class="divider--bottom text-align--center">
                    <h1><span class="text--muted">@lang('stats.date')</span> @lang('stats.range')</h1>
                </header>

                <div class="dateRange-buttons">
                    <button class="btn" data-range="allSeason"><i class="fa fa-calendar"></i> <span>@lang('stats.allSeason')</span></button>
                    <button class="btn" data-range="thisWeek"><i class="fa fa-calendar"></i> <span>@lang('stats.thisWeek')</span></button>
                    <button class="btn" data-range="lastWeek"><i class="fa fa-calendar"></i> <span>@lang('stats.lastWeek')</span></button>
                </div>

                <div id="dateRangePicker" class="dateRange-picker"></div>

            </section>
        </section>

        <section class="page-section section--empty bg--smoke">
            <div>
                @include('partials.doge-icon')
                <h1>@lang('misc.wowSuchEmpty')</h1>
                <p>@lang('stats.noStatsInRange')</p>
            </div>
        </section>

        <section class="page-section bg--smoke section--goalie">
            <div class="container">
                <header class="divider--bottom text-align--center">
                    <h1><span class="text--muted">@lang('stats.goalie')</span> @lang('stats.stats')</h1>
                </header>

                <section class="charts">
                    @include('partials.stats.goalie', ['stats' => false])
                </section>

                <?php
                $fields = [
                    'saves' => 0,
                    'goals_allowed' => 0,
                    'save_percent' => 0,
                    'advantage_goals_allowed' => 0,
                    'five_meters_taken_on' => 0,
                    'five_meters_blocked' => 0,
                    'five_meters_allowed' => 0,
                    'shoot_out_taken_on' => 0,
                    'shoot_out_blocked' => 0,
                    'shoot_out_allowed' => 0,
                ];
                ?>
                @foreach(['V', 'JV'] as $team)
                    <table class="table table--collapse table--fancyCaption stats-table aggregate-stats--goalie" data-team="{{$team}}" data-position="goalie">
                        <caption>@lang('misc.' . $team)</caption>

                        <thead>
                            <tr>
                                <th></th>
                                @foreach($fields as $key => $v)
                                    <th>@lang('stats.'.$key)</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot></tfoot>
                    </table>
                @endforeach

                <textarea id="goalieRow" class="template">
                    <tr>
                        <th data-field="playerName"><%= playerSeason.link %></th>
                        @foreach($fields as $key => $v)
                            <td class="stat--{{$key}}" data-field="{{$key}}" data-title="@lang('stats.'.$key)">
                                <span><%={{ $key }}%></span>
                            </td>
                        @endforeach
                    </tr>
                </textarea>
            </div>
        </section>

        <section class="page-section section--field">
            <div class="container">
                <header class="divider--bottom text-align--center">
                    <h1><span class="text--muted">@lang('stats.field')</span> @lang('stats.stats')</h1>
                </header>

                <section class="charts">
                    @include('partials.stats.field', ['stats' => false, 'drawSprints' => true])
                </section>

                <?php
                $fields = [
                    'goals' => 0,
                    'shots' => 0,
                    'shooting_percent' => 0,
                    'assists' => 0,
                    'steals' => 0,
                    'turnovers' => 0,
                    'steals_to_turnovers' => 0,
                    'blocks' => 0,
                    'kickouts' => 0,
                    'kickouts_drawn' => 0,
                    'advantage_goals' => 0,
                    'five_meters_called' => 0,
                    'five_meters_drawn' => 0,
                    'five_meters_taken' => 0,
                    'five_meters_made' => 0,
                    'sprints_taken' => 0,
                    'sprints_won' => 0,
                    'shoot_out_taken' => 0,
                    'shoot_out_made' => 0
                ];
                ?>
                @foreach(['V', 'JV'] as $team)
                    <table class="table table--condensed table--collapse table--fancyCaption stats-table game-stats--field" data-team="{{$team}}" data-position="field">
                        <caption>@lang('misc.' . $team)</caption>
                        <thead>
                            <tr>
                                <th rowspan="2">@lang('stats.name')</th>
                                <th rowspan="2">@lang('stats.goals')</th>
                                <th rowspan="2">@lang('stats.shots')</th>
                                <th rowspan="2">@lang('stats.shooting_percent')</th>
                                <th rowspan="2">@lang('stats.assists')</th>
                                <th rowspan="2">@lang('stats.steals')</th>
                                <th rowspan="2">@lang('stats.tos')</th>
                                <th rowspan="2" style="width: 30px;">@lang('stats.steals_to_tos')</th>
                                <th rowspan="2">@lang('stats.blocks')</th>
                                <th   colspan="3">@lang('stats.kickouts')</th>
                                <th   colspan="4">@lang('stats.five_meters')</th>
                                <th   colspan="2">@lang('stats.sprints')</th>
                                <th   colspan="2">@lang('stats.shoot_outs')</th>
                            </tr>
                            <tr>
                                <!-- kickouts -->
                                <th>@lang('stats.called')</th>
                                <th>@lang('stats.drawn')</th>
                                <th>@lang('stats.goals')</th>
                                <!-- 5 meters -->
                                <th>@lang('stats.called')</th>
                                <th>@lang('stats.drawn')</th>
                                <th>@lang('stats.taken')</th>
                                <th>@lang('stats.made')</th>
                                <!-- sprints -->
                                <th>@lang('stats.taken')</th>
                                <th>@lang('stats.won')</th>
                                <!-- shootouts -->
                                <th>@lang('stats.taken')</th>
                                <th>@lang('stats.made')</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot></tfoot>
                </table>
                @endforeach

                <textarea id="fieldRow" class="template">
                    <tr>
                        <th class="player-name" data-field="playerName"><%= playerSeason.link %></th>
                        @foreach($fields as $key => $v)
                            <td class="stat--{{$key}}" data-field="{{$key}}" data-title="@lang('stats.'.$key)">
                                <span><%={{$key}}%></span>
                            </td>
                        @endforeach
                    </tr>
                </textarea>

            </div>
        </section>

        <script type="application/json" id="playersData">
            @json($players)
        </script>

        <script type="application/json" id="statsData">
            @json($data)
        </script>


        <div class="block-loading block-loading--fullPage bg--dark">
            <div class="loader"></div>
        </div>

    </article>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="{{elixir('js/stats.js')}}"></script>
@endpush