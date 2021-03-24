<section class="stat stat--shooting stat--loading" data-chart="shooting">

    <div class="stat-chart-wrapper">
        <div class="stat-chart-sizer">
            <div class="stat-chart"></div>
        </div>

        <div class="stat-header">
            <h1 class="percent" data-field="shooting_percent">@if($stats) @number($stats->shooting_percent) @else &ndash; @endif</h1>

            <p
                ><span data-field="goals">{!! $stats ? $stats->goals : '&ndash;' !!}</span
                >/<span data-field="shots">{!! $stats ? $stats->shots : '&ndash;' !!}</span>
            </p>
        </div>
    </div>

    <h2>@lang('stats.shooting_percent')</h2>
</section>