<?php
$number = rand(200, 500);
?>
<section class="stat stat--thirsts-quenched stat--loading" data-chart="thirstsQuenched">

    <div class="stat-chart-wrapper">
        <div class="stat-chart-sizer">
            <div class="stat-chart"></div>
        </div>

        <div class="stat-header">
            <h1 class="percent">100</h1>

            <p>{{ $number }}/{{ $number }}</p>
        </div>
    </div>

    <h2>@lang('stats.thirstsQuenched')</h2>
    <h3>@lang('stats.thirsts')/@lang('stats.quenched')</h3>
</section>