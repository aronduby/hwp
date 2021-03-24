<section class="stat stat--assists stat--loading" data-chart="assists">

    <div class="stat-chart-wrapper">
        <div class="stat-chart-sizer">
            <div class="stat-chart"></div>
        </div>

        <div class="stat-header">
            <h1>
                @if($stats)
                    {{$stats->goals}}/{{$stats->assists}}
                @else
                    <span data-field="goals">&ndash;</span>/<span data-field="assists">&ndash;</span></h1>
                @endif
        </div>
    </div>

    <h2>@lang('stats.goals')/@lang('stats.assists')</h2>
</section>