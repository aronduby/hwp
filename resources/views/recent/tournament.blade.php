<div class="recent recent--tournament" data-recent-id="{{$recent->id}}">
    <div class="bg-elements">
        <div class="bg--dark-gray"></div>
        <div class="bg--img" @if($bg)style="background-image:url({{$bg}}"@endif()></div>
        <div class="bg--grid blend--overlay"></div>
        <div class="bg--bottom-third-primary"></div>
    </div>

    <a href="@route('tournament', ['id' => $tournament->id])" title="@lang('recent.viewTournament')">
        <div class="tag"><span><em>@lang('recent.tournament')</em></span></div>

        <h1>{{str_limit($tournament->recent_title, \App\Models\Recent::TITLE_LIMIT)}}</h1>

        <time datetime="@iso($recent->created_at)">@stamp($recent->created_at)</time>
    </a>

    <div class="recent--hover">
        <h1><i class="fa fa-site-map fa-rotate-90"></i>@lang('recent.viewTournament')</h1>
    </div>
</div>