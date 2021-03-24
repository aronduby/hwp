<section class="stat stat--steals-turnovers stat--loading" data-chart="stealsToTurnovers">

    <div class="stat-chart-wrapper">
        <div class="stat-chart-sizer">
            <div class="stat-chart"></div>
        </div>

        <div class="stat-header">
            <h1
                data-field="steals_to_turnovers"
                class="{{ $stats && $stats->steals_to_turnovers > 0 ? 'positive' : ($stats && $stats->steals_to_turnovers < 0 ? 'negative' : '') }}"
            >
                @if($stats)
                    {{ str_replace('-', '', number_format($stats->steals_to_turnovers)) }}
                @else
                    &ndash;
                @endif
            </h1>
            <p
                ><span data-field="steals">{!! $stats ? $stats->steals : '&ndash;'  !!}</span
                >/<span data-field="turnovers">{!! $stats ? $stats->turnovers : '&ndash;'  !!}</span>
            </p>
        </div>
    </div>

    <h2>@lang('stats.steals')/@lang('stats.turnovers')</h2>
</section>