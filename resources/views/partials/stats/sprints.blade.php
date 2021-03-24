<section class="stat stat--sprints stat--loading" data-chart="sprints">

    <div class="stat-chart-wrapper">
        <div class="stat-chart-sizer">
            <div class="stat-chart"></div>
        </div>

        <div class="stat-header">
            <h1 class="percent" data-field="sprints_percent">
                @if($stats)
                    @number($stats->sprints_percent)
                @else
                    &ndash;
                @endif
            </h1>
            <p
                ><span data-field="sprints_won">{!! $stats ? $stats->sprints_won : '&ndash;' !!}</span
                >/<span data-field="sprints_taken">{!! $stats ? $stats->sprints_taken : '&ndash;' !!}</span>
            </p>
        </div>
    </div>

    <h2>@lang('stats.sprints')</h2>
</section>