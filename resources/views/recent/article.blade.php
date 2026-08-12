<div class="recent recent--article" data-recent-id="{{$recent->id}}">
    <div class="bg-elements">
        <div class="bg--dark-gray"></div>
        <div class="bg--img"
            @if($article->photo)
                style="background-image:url({{$article->photo}})"
            @endif
        ></div>
        <div class="bg--grid blend--overlay"></div>
        <div class="bg--bottom-third-primary"></div>
    </div>

    <a href="{{$article->url}}" title="@lang('recent.viewArticle')" target="_blank">
        <div class="tag"><span><em>@lang('recent.article')</em></span></div>

        <h1>{{Str::limit($article->title, \App\Models\Recent::TITLE_LIMIT)}}</h1>

        <time datetime="@iso($article->published)">@stamp($article->published)</time>
    </a>

    <div class="recent--hover">
        <h1>@lang('recent.viewArticle') <i class="fa fa-external-link"></i></h1>
        @if($article->players->count())
            <p class="mentions">@lang('recent.mentions'):
                @foreach($article->players as $player)
                    <a href="@route('players', ['player' => $player->name_key])" title="{{$player->name}}">{{$player->name}}</a>
                @endforeach
            </p>
        @endif
    </div>
</div>
