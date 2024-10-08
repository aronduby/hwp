@inject('season', 'App\Models\ActiveSeason')

<header id="home-header" class="bg--dark @if($photo) hasPhoto @endif">
    <div class="bg-elements">
        <div class="bg--gradient"></div>
        @if($photo)
            <div class="slide bg--img" style="background-image: url({{$photo->photo}});"></div>
        @else
            <div class="slide bg--img" style="background-image: url('images/ezra-ball.png');"></div>
        @endif
    </div>

    <section class="container text-align--center text--shadow">
        <div>
            <header>
                @if($rankingTitle)
                    <h2 class="ranking text--white-darker">{{$rankingTitle}}</h2>
                @elseif($ranking)
                    <h2 class="ranking text--white-darker">@ordinal($ranking) @lang('misc.ranked')</h2>
                @endif
                <h1 class="site-name">Hudsonville<wbr><span class="text--accent">Water</span><wbr>Polo</h1>
                @if(!$season->current)
                    <h4 class="active-season--title text--white-darker">{{$season->title}}</h4>
                @endif
            </header>
            <section class="upcoming">
                <header class="divider--inline">
                    <h1>@lang('misc.Upcoming')</h1>
                </header>
                @if($upcoming)
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <section class="team varsity">
                                <header><h1>@lang('misc.V')</h1></header>
                                @if(isset($varsity))
                                    @include('partials.home.header.game', ['game' => $varsity])
                                @else
                                    @lang('misc.NoUpcomingEvents')
                                @endif
                            </section>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <section class="team jv">
                                <header><h1>@lang('misc.JV')</h1></header>
                                @if(isset($jv))
                                    @include('partials.home.header.game', ['game' => $jv])
                                @else
                                    @lang('misc.NoUpcomingEvents')
                                @endif
                            </section>
                        </div>
                    </div>
                @else
                    <p class="empty">@lang('misc.NoUpcomingEvents')</p>
                @endif
            </section>
        </div>
    </section>

    <div class="eyeToggle">
        <input id="homeHeader-photoToggle-cb" type="checkbox" autocomplete="off" />
        <label for="homeHeader-photoToggle-cb">
            <i class="eyeToggle-icon eyeToggle-icon--unchecked fa fa-eye"></i>
            <i class="eyeToggle-icon eyeToggle-icon--checked fa fa-eye-slash"></i>
        </label>
    </div>
</header>