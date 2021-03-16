@if($job['settings'] !== false)
    <template class="settings-modal-template">
        <article class="job-setting">
            <header class="bg--dark bg--grid">
                <h1>@lang('jobs.'.$job['key'].'.title')</h1>
            </header>
            <div class="body">
                @include('admin.jobs.partials.settings.'.$job['settings'], ['instance' => null])

                <div class="block-loading bg--dark">
                    <div class="loader"></div>
                </div>
            </div>
        </article>
    </template>
@endif