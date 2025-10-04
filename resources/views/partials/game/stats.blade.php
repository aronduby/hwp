@extends('game')

@section('title')
    @lang('game.stats') - {{$game->title}} -
@endsection

@section('game-content')

    <section class="page-section game-stats--final bg--dark bg--grid">
        <div class="container">
            <header class="divider--bottom text-align--center">
                <h1><span class="text--muted">@lang('stats.final')</span> @lang('stats.results')</h1>
            </header>

            <div class="row around-xs center-md">
                <div class="col-sm-5 col-md-4">
                    <section class="card result result--{{$statusUs}}">
                        <header class="bg--grid">
                            <h1>Hudsonville</h1>
                        </header>

                        <div class="body">
                            <div class="score">
                                <h2>{{$game->score_us}}</h2>
                            </div>
                        </div>

                        <footer class="clearfix">
                            <table class="box-score align--right">
                                <tbody>
                                <tr>
                                    @foreach($boxscoreQuarterUs as $i => $quarter)
                                        <td>{{$quarter->sum('goals')}}</td>
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        </footer>
                    </section>
                </div>
                <div class="col-sm-5 col-md-4">
                    <section class="card result result--{{$statusThem}}">
                        <header class="bg--grid">
                            <h1>{{$game->opponent}}</h1>
                        </header>
                        <div class="body">
                            <div class="score">
                                <h2>{{$game->score_them}}</h2>
                            </div>
                        </div>

                        <footer class="clearfix">
                            <table class="box-score align--right">
                                <tbody>
                                <tr>
                                    @foreach($boxscoreQuarterThem as $i => $quarter)
                                        <td>{{$quarter->sum('goals')}}</td>
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        </footer>
                    </section>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section game-stats--goalie bg--smoke">
        <div class="container">
            <header class="divider--bottom text-align--center">
                <h1><span class="text--muted">@lang('stats.goalie')</span> @lang('stats.stats')</h1>
            </header>

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
            <table class="table table--collapse stats-table game-stats--goalie">
                <thead>
                    <tr>
                        <th></th>
                        @foreach($fields as $key => $v)
                            <th>@lang('stats.'.$key)</th>
                        @endforeach
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($goalies as $stat)
                        <tr>
                            <th>@playerSeasonLink($stat->player, $game->team)</th>
                            @foreach($fields as $key => &$sum)
                                <td class="stat--{{$key}}" data-title="@lang('stats.'.$key)">
                                    <span>@number($stat->$key)</span></td>
                                <?php
                                $fields[$key] += $stat->$key;
                                ?>
                            @endforeach
                            <td class="stat-btns action-btns">
                                @if($stat->isShareable())
                                    <a class="btn shareable" data-shareable-type="gamePlayer" href="{!! $stat->getShareableUrl() !!}" title="@lang('misc.shareable')"  target="_blank">
                                        <i class="fa fa-share-alt-square"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                @if(count($goalies) > 1)
                    <?php
                    $totals = new \App\Models\Stat($fields);
                    ?>
                    <tfoot>
                        <tr>
                            <th>@lang('stats.totals')</th>
                            @foreach($fields as $key => $sum)
                                <td class="stat--{{$key}}" data-title="@lang('stats.'.$key)">
                                    <span>@number($totals->$key)</span></td>
                            @endforeach
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </section>

    <section class="page-section game-stats--field">
        <div class="container">
            <header class="divider--bottom text-align--center">
                <h1><span class="text--muted">@lang('stats.field')</span> @lang('stats.stats')</h1>
            </header>

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
            <table class="table table--condensed table--collapse stats-table game-stats--field">
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
                    <th colspan="3">@lang('stats.kickouts')</th>
                    <th colspan="4">@lang('stats.five_meters')</th>
                    <th colspan="2">@lang('stats.sprints')</th>
                    <th colspan="2">@lang('stats.shoot_outs')</th>
                    <th rowspan="2"></th>
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
                <tbody>
                    @foreach($players as $stat)
                        <tr>
                            <th class="player-name">@playerSeasonLink($stat->player, $game->team)</th>
                            @foreach($fields as $key => &$sum)
                                <td class="stat--{{$key}}" data-title="@lang('stats.'.$key)">
                                    <span>@numberOrNothing($stat->$key)</span>
                                </td>
                                <?php
                                $fields[$key] += $stat->$key;
                                ?>
                            @endforeach
                            <td class="stat-btns action-btns">
                                @if($stat->isShareable())
                                    <a class="btn shareable" data-shareable-type="gamePlayer" href="{!! $stat->getShareableUrl() !!}" title="@lang('misc.shareable')"  target="_blank">
                                        <i class="fa fa-share-alt-square"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <?php
                        $totals = new \App\Models\Stat($fields);
                        ?>
                        <th>@lang('stats.totals')</th>
                        @foreach($fields as $key => $sum)
                            <td class="stat--{{$key}}" data-title="@lang('stats.'.$key)"><span>@number($totals->$key)</span>
                            </td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>


    <section class="page-section game-stats--box-score bg--smoke">
        <div class="container">
            <header class="divider--bottom text-align--center">
                <h1><span class="text--muted">@lang('stats.goals')</span> @lang('stats.perQuarter')</h1>
            </header>

            @foreach($chunkedQuarters as $row)
                <div class="row around-xs center-md">
                    @foreach($row as $quarterNumber)
                        <?php
                        $us = $boxscores->us()->quarter($quarterNumber);
                        $them = $boxscores->them()->quarter($quarterNumber);
                        ?>
                        <div class="col-xs-12 col-md-3">
                            <section class="card box-score">
                                <header class="bg--dark bg--grid">
                                    <h1>@ordinal($quarterNumber)</h1>
                                </header>

                                <table class="body table table--striped">
                                    <tbody>
                                        <tr class="team-name">
                                            <th>Hudsonville</th>
                                            <th>{{$us->sum('goals')}}</th>
                                        </tr>
                                        @forelse($us as $goal)
                                            <tr>
                                                <th>@playerSeasonLink($goal->player, $game->team)</th>
                                                <td>{{$goal->goals}}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2">&ndash;</td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                    <tbody>
                                        <tr class="team-name">
                                            <th>{{$game->opponent}}</th>
                                            <th>{{$them->sum('goals')}}</th>
                                        </tr>
                                        @forelse($them as $goal)
                                            <tr>
                                                <th>{{$goal->name}}</th>
                                                <td>{{$goal->goals}}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2">&ndash;</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </section>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

    <section class="page-section game-stats--advantages-converted">
        <div class="container">
            <header class="divider--bottom text-align--center">
                <h1><span class="text--muted">@lang('stats.advantages')</span> @lang('stats.converted')</h1>
            </header>

            <div class="row around-xs center-md">
                <div class="col-sm-5 col-md-4">
                    <section class="card result">
                        <header class="bg--grid bg--dark">
                            <h1>Hudsonville</h1>
                        </header>
                        <div class="body">
                            <div class="score">
                                <div>{{$advantages->us()->converted}}</div>
                                <div>{{$advantages->us()->drawn}}</div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-sm-5 col-md-4">
                    <section class="card result">
                        <header class="bg--grid bg--dark">
                            <h1>{{$game->opponent}}</h1>
                        </header>
                        <div class="body">
                            <div class="score">
                                <div>{{$advantages->them()->converted}}</div>
                                <div>{{$advantages->them()->drawn}}</div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>

@endsection