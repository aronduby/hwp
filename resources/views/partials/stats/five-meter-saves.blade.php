<section class="stat stat--five-meter-saves stat--loading" data-chart="fiveMeterSaves">

    <div class="stat-chart-wrapper">
        <div class="stat-chart-sizer">
            <div class="stat-chart"></div>
        </div>

        <div class="stat-header">
            <h1 class="percent" data-field="five_meters_save_percent">@if($stats) @number($stats->five_meters_save_percent) @else &nbsp; @endif</h1>
            <p
                ><span data-field="five_meters_blocked">{!! $stats ? $stats->five_meters_blocked : '&ndash;' !!}</span
                >/<span data-field="five_meters_missed">{!! $stats ? $stats->five_meters_missed : '&ndash;' !!}</span
                >/<span data-field="five_meters_allowed">{!! $stats ? $stats->five_meters_allowed : '&ndash;' !!}</span
            ></p>
        </div>
    </div>


    <h2>@lang('stats.five_meters')</h2>
    <h3>@lang('stats.blocked')/@lang('stats.missed')/@lang('stats.allowed')</h3>
</section>