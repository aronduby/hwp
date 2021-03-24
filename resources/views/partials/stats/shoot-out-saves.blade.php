<section class="stat stat--shoot-out-saves stat--loading" data-chart="shootOutSaves">

    <div class="stat-chart-wrapper">
        <div class="stat-chart-sizer">
            <div class="stat-chart"></div>
        </div>

        <div class="stat-header">
            <h1 class="percent" data-field="shoot_out_save_percent">
                @if($stats)
                    @number($stats->shoot_out_save_percent)
                @else
                    &ndash;
                @endif
            </h1>
            <p
                ><span data-field="shoot_out_blocked">{!! $stats ? $stats->shoot_out_blocked : '&ndash;' !!}</span
                >/<span data-field="shoot_out_missed">{!! $stats ? $stats->shoot_out_missed : '&ndash;' !!}</span
                >/<span data-field="shoot_out_allowed">{!! $stats ? $stats->shoot_out_allowed : '&ndash;' !!}</span>
            </p>
        </div>
    </div>


    <h2>@lang('stats.shoot_outs')</h2>
    <h3>@lang('stats.blocked')/@lang('stats.missed')/@lang('stats.allowed')</h3>
</section>