<header class="page-header">
    <div class="bg-elements">
        <div class="bg--gradient"></div>
        <div class="bg--img" @if($headerPhoto)style="background-image: url({{$headerPhoto}});"@endif></div>
    </div>
    <div class="container">
        <h1>{{$tournament->title}} <small class="text--muted">@dateSpan($tournament->start, $tournament->end)</small></h1>

        @if($tournament->album_count > 0)
            <nav class="tournament-nav sub-nav">
                <ul>
                    <li class="@active('tournament')">
                        <a href="@route('tournament', ['tournament' => $tournament->id])"
                           title="@lang('tournament.games')"
                        ><i class="fa fa-sitemap fa-rotate-90"></i> @lang('tournament.games')</a>
                    </li>
                    <li class="@active('tournament.photos')">
                        <a href="@route('tournament.photos', ['tournament' => $tournament->id])"
                           title="@lang('photos.photos')"
                        ><i class="fa fa-picture-o"></i> @lang('photos.photos')</a>
                    </li>
                </ul>
            </nav>
        @endif
    </div>
</header>