
<section class="stat stat--shirts-delivered stat--loading" data-chart="shirtsDelivered">

    <div class="stat-chart-wrapper">
        <div class="stat-chart-sizer">
            <div class="stat-chart"></div>
        </div>

        <div class="stat-header">
            <h1 class="percent" data-field="delivery_percent">{{ floor(($stats['part'] / $stats['whole']) * 100) }}</h1>

            <p>{{ $stats['part'] }}/{{ $stats['whole'] }}</p>
        </div>
    </div>

    <h2>@lang('stats.shirtsDelivered')</h2>
    <h3>@lang('stats.delivered')/@lang('stats.total')</h3>
    <script>
        window.shirtsDeliveredData = <?= json_encode($stats) ?>;
    </script>
</section>