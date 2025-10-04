@extends('game')

@section('title')
    @lang('misc.edit') @lang('game.stats') - {{$game->title}} -
@endsection

@section('game-content')
<form method="post" data-autogenerate-score-status="<?= old('autogenerate-score', true) ? 'on' : 'off' ?>">

    {{ csrf_field() }}

    @if(count($errors) > 0)
       <section class="page-section errors">
           <div class="alert alert-danger">
               <ul>
                   @foreach ($errors->all() as $error)
                       <li>{{ $error }}</li>
                   @endforeach
               </ul>
           </div>
       </section>
    @endif

    <section class="page-section game-stats--final bg--dark bg--grid">
        <div class="container">
            <header class="divider--bottom text-align--center">
                <h1><span class="text--muted">@lang('stats.final')</span> @lang('stats.results')</h1>
            </header>

            <div class="autogenerate-holder text-align--center">
                <label>
                    <input
                        id="autogenerate-score-toggle"
                        type="checkbox"
                        name="autogenerate-score"
                        class="toggler autogenerate-score-toggle"
                        value="1"
                        @if(old('autogenerate-score', true))
                            checked
                        @endif
                    />
                    <label for="autogenerate-score-toggle"></label>
                    @lang('stats.autogenerateScore')
                </label>
            </div>


            <div class="row around-xs center-md">
                <div class="col-sm-5 col-md-4">
                    <section class="card result result--us" data-result="{{$statusUs}}">
                        <header class="bg--grid">
                            <h1>Hudsonville</h1>
                        </header>

                        <div class="body">
                            <div class="score">
                                <h2
                                    data-autogenerate-score="on"
                                    data-autogenerate-score-value="score_us"
                                >{{$game->score_us}}</h2>
                                <input
                                    type="number"
                                    class="form-control game-score"
                                    data-autogenerate-score="off"
                                    data-autogenerate-score-value="score_us"
                                    name="score_us"
                                    value="@val('score_us', $game->score_us)"
                                />
                            </div>
                        </div>
                    </section>
                </div>
                <div class="col-sm-5 col-md-4">
                    <section class="card result result--them" data-result="{{$statusThem}}">
                        <header class="bg--grid">
                            <h1>{{$game->opponent}}</h1>
                        </header>
                        <div class="body">
                            <div class="score">
                                <h2
                                    data-autogenerate-score="on"
                                    data-autogenerate-score-value="score_them"
                                >{{$game->score_them}}</h2>
                                <input
                                    type="number"
                                    class="form-control game-score"
                                    data-autogenerate-score="off"
                                    data-autogenerate-score-value="score_them"
                                    name="score_them"
                                    value="@val('score_them', $game->score_them)"
                                />
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>

    <!-- goalies -->
    <section class="page-section edit-stats bg--smoke">
        <div class="container">
            <header class="divider--bottom text-align--center">
                <h1><span class="text--muted">@lang('stats.goalie')</span> @lang('stats.stats')</h1>
            </header>

            <table class="table table--condensed stats-table-edit">
                <thead>
                    <tr>
                        <th>@lang('stats.name')</th>
                        <th>@lang('stats.saves')</th>
                        <th>@lang('stats.goals_allowed')</th>
                        <th>@lang('stats.advantage_goals_allowed')</th>
                        <th>@lang('stats.five_meters_taken_on')</th>
                        <th>@lang('stats.five_meters_blocked')</th>
                        <th>@lang('stats.five_meters_allowed')</th>
                        <th>@lang('stats.shoot_out_taken_on')</th>
                        <th>@lang('stats.shoot_out_blocked')</th>
                        <th>@lang('stats.shoot_out_allowed')</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = -1; ?>
                @foreach($goalies as $key => $goalieStats)
                    <?php
                    $i++;
                    $name = "goalie[$i]";
                    ?>
                    <tr data-i="{{$i}}">
                        <td>
                            <select name="{{$name}}[player_id]" class="form-control player-picker">
                                <option></option>
                                @foreach(['V', 'JV'] as $team)
                                    <optgroup label="@lang('misc.'.$team)">@lang('misc.'.$team)</optgroup>
                                    @foreach($playerlist->team($team)->sortByNumber($team) as $playerSeason)
                                        <option value="{{$playerSeason->player_id}}"
                                            @if($playerSeason->player_id == $goalieStats->player_id)selected @endif
                                        >#{{$playerSeason->getNumber($team)}} {{$playerSeason->name}}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[saves]" value="@val($name.'[saves]', $goalieStats->saves)" />
                        </td>
                        <td>
                            <input
                                class="form-control"
                                type="number"
                                name="{{$name}}[goals_allowed]"
                                value="@val($name.'[goals_allowed]', $goalieStats->goals_allowed)"
                                data-autogenerate-score-source="score_them"
                            />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[advantage_goals_allowed]" value="@val($name.'[advantage_goals_allowed]', $goalieStats->advantage_goals_allowed)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[five_meters_taken_on]" value="@val($name.'[five_meters_taken_on]', $goalieStats->five_meters_taken_on)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[five_meters_blocked]" value="@val($name.'[five_meters_blocked]', $goalieStats->five_meters_blocked)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[five_meters_allowed]" value="@val($name.'[five_meters_allowed]', $goalieStats->five_meters_allowed)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[shoot_out_taken_on]" value="@val($name.'[shoot_out_taken_on]', $goalieStats->shoot_out_taken_on)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[shoot_out_blocked]" value="@val($name.'[shoot_out_blocked]', $goalieStats->shoot_out_blocked)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[shoot_out_allowed]" value="@val($name.'[shoot_out_allowed]', $goalieStats->shoot_out_allowed)" />
                        </td>
                        <td>
                            <button title="@lang('misc.remove')" class="btn btn--small btn-hover--danger remove-row">
                                <i class="fa fa-minus"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <p class="text-align--center">
                <button title="@lang('misc.add')" class="btn btn--default add-row">
                    <i class="fa fa-plus"></i> @lang('misc.add')
                </button>
            </p>
        </div>
    </section>

    <!-- players -->
    <section class="page-section edit-stats">
        <div class="container">
            <header class="divider--bottom text-align--center">
                <h1><span class="text--muted">@lang('stats.field')</span> @lang('stats.stats')</h1>
            </header>

            <table class="table table--condensed stats-table-edit stats-table--hasTotals">
                <thead>
                    <tr>
                        <th rowspan="2">@lang('stats.name')</th>
                        <th rowspan="2">@lang('stats.shots')</th>
                        <th colspan="7">@lang('stats.goals')</th>
                        <th rowspan="2">@lang('stats.assists')</th>
                        <th rowspan="2">@lang('stats.steals')</th>
                        <th rowspan="2">@lang('stats.blocks')</th>
                        <th rowspan="2">@lang('stats.tos')</th>
                        <th colspan="3">@lang('stats.kickouts')</th>
                        <th colspan="2">@lang('stats.sprints')</th>
                        <th colspan="4">@lang('stats.five_meters')</th>
                        <th rowspan="2"></th>
                    </tr>
                    <tr>
                        <!-- goals -->
                        <th>I</th>
                        <th>II</th>
                        <th>III</th>
                        <th>IV</th>
                        <th>OT I</th>
                        <th>OT II</th>
                        <th>SO</th>
                        <!-- kickouts -->
                        <th>@lang('stats.called')</th>
                        <th>@lang('stats.drawn')</th>
                        <th>@lang('stats.goals')</th>
                        <!-- sprints -->
                        <th>@lang('stats.taken')</th>
                        <th>@lang('stats.won')</th>
                        <!-- 5 meters -->
                        <th>@lang('stats.drawn')</th>
                        <th>@lang('stats.taken')</th>
                        <th>@lang('stats.made')</th>
                        <th>@lang('stats.called')</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = -1; ?>
                @foreach($players as $key => $playerStats)
                    <?php
                    $i++;
                    $name = "stats[$i]";
                    ?>
                    <tr data-i="{{$i}}">
                        <td>
                            <select name="{{$name}}[player_id]" class="form-control player-picker">
                                <option></option>
                                @foreach(['V', 'JV'] as $team)
                                    <optgroup label="@lang('misc.'.$team)">@lang('misc.'.$team)</optgroup>
                                    @foreach($playerlist->team($team)->sortByNumber($team) as $playerSeason)
                                        <option value="{{$playerSeason->player_id}}"
                                                @if($playerSeason->player_id == $playerStats->player_id && $game->team === $playerSeason->team)selected @endif
                                        >#{{$playerSeason->getNumber($team)}} {{$playerSeason->name}}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[shots]" value="@val($name.'[shots]', $playerStats->shots)" />
                        </td>
                        <td>
                            <input
                                class="form-control"
                                type="number"
                                name="{{$name}}[goals][1]"
                                value="@val($name.'[goals][1]', $playerStats->goalsPerQuarter[1])"
                                data-autogenerate-score-source="score_us"
                            />
                        </td>
                        <td>
                            <input
                                class="form-control"
                                type="number"
                                name="{{$name}}[goals][2]"
                                value="@val($name.'[goals][2]', $playerStats->goalsPerQuarter[2])"
                                data-autogenerate-score-source="score_us"
                            />
                        </td>
                        <td>
                            <input
                                class="form-control"
                                type="number"
                                name="{{$name}}[goals][3]"
                                value="@val($name.'[goals][3]', $playerStats->goalsPerQuarter[3])"
                                data-autogenerate-score-source="score_us"
                            />
                        </td>
                        <td>
                            <input
                                class="form-control"
                                type="number"
                                name="{{$name}}[goals][4]"
                                value="@val($name.'[goals][4]', $playerStats->goalsPerQuarter[4])"
                                data-autogenerate-score-source="score_us"
                            />
                        </td>
                        <td>
                            <input
                                class="form-control"
                                type="number"
                                name="{{$name}}[goals][5]"
                                value="@val($name.'[goals][5]', $playerStats->goalsPerQuarter[5])"
                                data-autogenerate-score-source="score_us"
                            />
                        </td>
                        <td>
                            <input
                                class="form-control"
                                type="number"
                                name="{{$name}}[goals][6]"
                                value="@val($name.'[goals][6]', $playerStats->goalsPerQuarter[6])"
                                data-autogenerate-score-source="score_us"
                            />
                        </td>
                        <td>
                            <input
                                class="form-control"
                                type="number"
                                name="{{$name}}[goals][7]"
                                value="@val($name.'[goals][7]', $playerStats->goalsPerQuarter[7])"
                                data-autogenerate-score-source="score_us"
                            />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[assists]" value="@val($name.'[assists]', $playerStats->assists)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[steals]" value="@val($name.'[steals]', $playerStats->steals)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[blocks]" value="@val($name.'[blocks]', $playerStats->blocks)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[turnovers]" value="@val($name.'[turnovers]', $playerStats->turnovers)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[kickouts]" value="@val($name.'[kickouts]', $playerStats->kickouts)" max="3" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[kickouts_drawn]" value="@val($name.'[kickouts_drawn]', $playerStats->kickouts_drawn)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[advantage_goals]" value="@val($name.'[advantage_goals]', $playerStats->advantage_goals)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[sprints_taken]" value="@val($name.'[sprints_taken]', $playerStats->sprints_taken)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[sprints_won]" value="@val($name.'[sprints_won]', $playerStats->sprints_won)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[five_meters_drawn]" value="@val($name.'[five_meters_drawn]', $playerStats->five_meters_drawn)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[five_meters_taken]" value="@val($name.'[five_meters_taken]', $playerStats->five_meters_taken)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[five_meters_made]" value="@val($name.'[five_meters_made]', $playerStats->five_meters_made)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="{{$name}}[five_meters_called]" value="@val($name.'[five_meters_called]', $playerStats->five_meters_called)" />
                        </td>
                        <td>
                            <button title="@lang('misc.remove')" class="btn btn--small btn-hover--danger remove-row">
                                <i class="fa fa-minus"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        @for ($i = 0; $i < 22; $i++)
                            <td></td>
                        @endfor
                    </tr>
                </tfoot>
            </table>

            <p class="text-align--center">
                <button title="@lang('misc.add')" class="btn btn--default add-row">
                    <i class="fa fa-plus"></i> @lang('misc.add')
                </button>
            </p>
        </div>
    </section>

    <!-- opponent boxscore -->
    <section class="page-section edit-stats bg--smoke">
        <div class="container">
            <header class="divider--bottom text-align--center">
                <h1><span class="text--muted">@lang('stats.opponent')</span> @lang('stats.goals')</h1>
            </header>

            <table class="table table--condensed stats-table-edit">
                <thead>
                    <tr>
                        <th rowspan="2">@lang('stats.name')</th>
                        <th colspan="7">@lang('stats.goals')</th>
                        <th rowspan="2"></th>
                    </tr>
                    <tr>
                        <th>I</th>
                        <th>II</th>
                        <th>III</th>
                        <th>IV</th>
                        <th>OT I</th>
                        <th>OT II</th>
                        <th>SO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = -1; ?>
                    @foreach($opponentGoals as $playerName => $quarters)
                        <?php
                        $i++;
                        $name = "opponent[$i]";
                        ?>
                        <tr data-i="{{$i}}">
                            <td>
                                <input class="form-control" type="text" name="{{$name}}[name]" value="@val($name.'[name]', $playerName)">
                            </td>
                            <td>
                                <input class="form-control" type="number" name="{{$name}}[goals][1]" value="@val($name.'[goals][0]', $quarters->quarter(1)->sum('goals'))" />
                            </td>
                            <td>
                                <input class="form-control" type="number" name="{{$name}}[goals][2]" value="@val($name.'[goals][1]', $quarters->quarter(2)->sum('goals'))" />
                            </td>
                            <td>
                                <input class="form-control" type="number" name="{{$name}}[goals][3]" value="@val($name.'[goals][2]', $quarters->quarter(3)->sum('goals'))" />
                            </td>
                            <td>
                                <input class="form-control" type="number" name="{{$name}}[goals][4]" value="@val($name.'[goals][3]', $quarters->quarter(4)->sum('goals'))" />
                            </td>
                            <td>
                                <input class="form-control" type="number" name="{{$name}}[goals][5]" value="@val($name.'[goals][4]', $quarters->quarter(5)->sum('goals'))" />
                            </td>
                            <td>
                                <input class="form-control" type="number" name="{{$name}}[goals][6]" value="@val($name.'[goals][5]', $quarters->quarter(6)->sum('goals'))" />
                            </td>
                            <td>
                                <input class="form-control" type="number" name="{{$name}}[goals][7]" value="@val($name.'[goals][6]', $quarters->quarter(7)->sum('goals'))" />
                            </td>
                            <td>
                                <button title="@lang('misc.remove')" class="btn btn--small btn-hover--danger remove-row">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="text-align--center">
                <button title="@lang('misc.add')" class="btn btn--default add-row">
                    <i class="fa fa-plus"></i> @lang('misc.add')
                </button>
            </p>
        </div>
    </section>

    <!-- advantages converted -->
    <section class="page-section edit-stats">
        <div class="container">
            <header class="divider--bottom text-align--center">
                <h1><span class="text--muted">@lang('stats.advantages')</span> @lang('stats.converted')</h1>
            </header>

            <table class="table table--condensed stats-table-edit">
                <thead>
                    <tr>
                        <th>@lang('stats.team')</th>
                        <th>@lang('stats.attempts')</th>
                        <th>@lang('stats.made')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Hudsonville</th>
                        <td>
                            <input type="hidden" name="advantages[us][team]" value="US">
                            <input class="form-control" type="number" name="advantages[us][drawn]" value="@val('advantages[us][drawn]', $advantages->us()->drawn)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="advantages[us][converted]" value="@val('advantages[us][converted]', $advantages->us()->converted)" />
                        </td>
                    </tr>

                    <tr>
                        <th>{{$game->opponent}}</th>
                        <td>
                            <input type="hidden" name="advantages[them][team]" value="THEM">
                            <input class="form-control" type="number" name="advantages[them][drawn]" value="@val('advantages[them][drawn]', $advantages->them()->drawn)" />
                        </td>
                        <td>
                            <input class="form-control" type="number" name="advantages[them][converted]" value="@val('advantages[them][converted]', $advantages->them()->converted)" />
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr />

            <p class="text-align--center">
                <button
                        title="@lang('misc.save')"
                        class="btn btn--lg btn--save btn--primary"
                >
                    <i class="fa fa-floppy-o"></i> @lang('misc.save')
                </button>
            </p>
        </div>
    </section>

</form>
@endsection

@push('scripts')
<script src="js/statEdit.js"></script>
@endpush