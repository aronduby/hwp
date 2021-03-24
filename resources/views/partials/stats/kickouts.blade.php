<section class="stat stat--kickouts stat--loading" data-chart="kickouts">

    <div class="stat-chart-wrapper">
        <div class="stat-chart-sizer">
            <div class="stat-chart"></div>
        </div>

        <div class="stat-header">
            <h1
                data-field="kickouts_drawn_to_called"
                class="{{ $stats && $stats->kickouts_drawn_to_called > 0 ? 'positive' : ($stats && $stats->kickouts_drawn_to_called < 0 ? 'negative' : '') }}"
            >
                @if($stats)
                    {{ str_replace('-', '', $stats->kickouts_drawn_to_called) }}
                @else
                    &ndash;
                @endif
            </h1>

            <p
                ><span data-field="kickouts_drawn">{!! $stats ? $stats->kickouts_drawn : '&ndash;' !!}</span
                >/<span data-field="kickouts">{!! $stats ? $stats->kickouts : '&ndash;' !!}</span
            ></p>
        </div>
    </div>

    <h2>@lang('stats.kickouts')</h2>
    <h3>@lang('stats.drawn')/@lang('stats.called')</h3>
</section>