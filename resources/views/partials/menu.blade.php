@inject('activeSeason', 'App\Models\ActiveSeason')
@inject('activeSite', 'App\Models\ActiveSite')

<section id="main-menu" class="main-menu">
    <div id="trigger">
        <i class="fa fa-bars"></i>
        @warn(!$activeSeason->current, menu.notViewingCurrentSeason)
    </div>
    @if ($activeSite->picker)
        <div class="team-picker">
            <a href="//{{$activeSite->picker->domain}}.{{app('App\Http\Requests\Request')->getTLD()}}" class="text--smoke" title="team picker"><i class="fa fa-th-large"></i></a>
        </div>
    @endif
    <div class="nav-brand">
        <h1>
            <a class="text--white" href="@route('home')" title="@lang('menu.home')">
                H<span class="text--accent">W</span>P
                @if ($activeSite->subtitle)
                    <span class="text--muted">{{$activeSite->subtitle}}</span>
                @endif
            </a>
        </h1>
    </div>
    @include('partials.menu.desktop')
</section>