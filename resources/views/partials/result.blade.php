<section class="card result result--{{$result->status()}}" data-id="{{$result->id}}">
    <header>
        <h1 title="@lang('misc.'.$result->team) @lang('misc.vs') {{$result->opponent}}">@lang('misc.'.$result->team) <small>@lang('misc.vs')</small> {{$result->opponent}}</h1>
    </header>

    <div class="body">
        <ul>
            <li class="score">
                <div class="score--us">{{$result->score_us}}</div>
                <div class="score--them">{{$result->score_them}}</div>
            </li>
            <li>
                <i class="fa fa-fw fa-map-marker location"></i>{{$result->location->title}}
            </li>
            <li>
                <i class="fa fa-fw fa-calendar date"></i>@stamp($result->start)
            </li>
        </ul>
    </div>

    @if($result->stats_count > 0 || $result->album_count > 0 || $result->updates_count > 0 || ($result instanceof \App\Models\Contracts\Shareable && $result->isShareable()))
        <footer class="btn-group btn-group--full">
            @if($result->stats_count > 0)
                <a class="btn" href="@route('game.stats', ['id'=>$result->id])" title="@lang('misc.stats')">
                    <i class="fa fa-line-chart"></i>
                </a>
            @endif
            @if($result->album_count > 0)
                <a class="btn" href="@route('game.photos', ['id'=>$result->id])" title="@lang('misc.photos')">
                    <i class="fa fa-picture-o"></i>
                </a>
            @endif
            @if($result->updates_count > 0)
                <a class="btn" href="@route('game.recap', ['id'=>$result->id])" title="@lang('misc.recap')">
                    <i class="fa fa-ticket"></i>
                </a>
            @endif
            @if($result instanceof \App\Models\Contracts\Shareable && $result->isShareable())
                <a class="btn shareable" data-shareable-type="game" href="{!! $result->getShareableUrl() !!}" title="@lang('misc.shareable')" target="_blank">
                    <i class="fa fa-share-alt-square"></i>
                </a>
            @endif
        </footer>
    @endif

</section>