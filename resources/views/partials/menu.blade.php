@inject('activeSeason', 'App\Models\ActiveSeason')

<section id="main-menu" class="main-menu">
    <div id="trigger">
        <i class="fa fa-bars"></i>
        @warn(!$activeSeason->current, menu.notViewingCurrentSeason)
    </div>
    <div class="nav-brand">
        <h1>
            <a class="text--white" href="@route('home')" title="@lang('menu.home')">
                H<span class="text--accent">W</span>P
                @if (config('site.title.suffix'))
                    <span class="text--muted">{{config('site.title.suffix')}}</span>
                @endif
            </a>
        </h1>
    </div>
    @include('partials.menu.desktop')
</section>