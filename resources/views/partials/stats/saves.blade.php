<section class="stat stat--saves stat--loading" data-chart="saves">

    <div class="stat-chart-wrapper">
        <div class="stat-chart-sizer">
            <div class="stat-chart"></div>
        </div>

        <div class="stat-header">
            <h1 class="percent" data-field="save_percent">@if($stats) @number($stats->save_percent) @else &ndash; @endif</h1>
            <p>
                <span data-field="saves">{!! $stats ? $stats->saves : '&ndash;' !!}</span>/<span data-field="goals_allowed">{!! $stats ? $stats->goals_allowed : '&ndash;'!!}</span>
            </p>
        </div>
    </div>

    <h2>@lang('stats.saves')</h2>
</section>