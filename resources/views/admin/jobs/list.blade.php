@extends('layouts.app')

@section('title')
    @lang('jobs.siteJobs') -
@endsection

@section('content')
    <article class="page--jobs">
        <header class="page-header header--small">
            <div class="bg-elements">
                <div class="bg--gradient"></div>
                <div class="bg--img"></div>
            </div>
            <div class="container">
                <h1>@lang('jobs.site') <span class="text--muted">@lang('jobs.jobs')</span></h1>
            </div>
        </header>

        <section class="page-section container">
            <div class="row middle-md">
                <p class="col-md-10">@lang('jobs.description')</p>
                <p class="col-md-2 text-align--right">
                    <a class="btn btn--accent" href="mailto:aron.duby@gmail.com" title="@lang('jobs.suggestAJob')">
                        <i class="fa fa-paper-plane"></i>
                        <span>@lang('jobs.suggestAJob')</span>
                    </a>
                </p>
            </div>
        </section>

        <section class="page-section container">
            <table class="table table--stripedBodies table--collapse table--fancyHeader jobList">
                <thead class="bg--grid bg--dark">
                    <tr>
                        <th class="jobList-job">@lang('jobs.job')</th>
                        <th class="jobList-status">@lang('jobs.status')</th>
                        <th class="jobList-autoRun">@lang('jobs.autoRun')</th>
                        <th class="jobList-actions">@lang('jobs.actions')</th>
                    </tr>
                </thead>

                @foreach($jobs as $job)

                    @if($job['allowMultiple'])
                        @include('admin.jobs.partials.job.multi-instance', [
                            'job' => $job,
                            'instances' => array_key_exists($job['job'], $instances) ? $instances[$job['job']] : []
                        ])
                    @else
                        @include('admin.jobs.partials.job.single-instance', [
                            'job' => $job,
                            'instance' => array_key_exists($job['job'], $instances) ? $instances[$job['job']][0] : false
                        ])
                    @endif

                @endforeach

            </table>
        </section>

    </article>
@endsection

@push('scripts')
    <script src="{{ mix('js/jobs.js') }}"></script>
@endpush