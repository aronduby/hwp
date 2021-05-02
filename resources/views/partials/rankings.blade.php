<aside class="card card--no-shadow rankings">
    <header class="bg--grid bg--primary has-controls text-align--left">
        <h1>@lang('misc.rankings')</h1>
        {{-- note that these are backwards so next page is the prev link --}}
        <ul class="pager">
            <li class="next {{ !$rankings->nextPageUrl() ? 'disabled' : '' }}">
                <a href="{{$rankings->nextPageUrl()}}"><i class="fa fa-chevron-left"></i></a>
            </li>
            <li class="prev {{ !$rankings->previousPageUrl() ? 'disabled' : '' }}">
                <a href="{{$rankings->previousPageUrl()}}"><i class="fa fa-chevron-right"></i></a>
            </li>
        </ul>
    </header>

    <table class="body rankings table table--striped">
        @if($rankings->count() >= 1)
            <tbody>
                @forelse($rankings->first()->ranks as $rank)
                    <tr class="rank {{$rank->self ? 'rank--self' : ''}}">
                        <th class="rank-rank">{{$rank->rank}}</th>
                        <td class="rank-team">{{$rank->team}} {{$rank->tied ? '('.trans('misc.tied').')' : ''}}</td>
                        <td class="rank-points">{{$rank->points ? number_Format($rank->points, 0) : ''}}</td>
                    </tr>
                @empty
                    <tr><td colspan="2">@include('partials.nothing-here-yet')</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">@dateSpan($rankings->first()->start, $rankings->first()->end)</td>
                </tr>
            </tfoot>
        @else
            <tbody>
                <tr><td>@include('partials.nothing-here-yet')</td></tr>
            </tbody>
        @endif
    </table>

    <div class="block-loading bg--dark">
        <div class="loader"></div>
    </div>
</aside>