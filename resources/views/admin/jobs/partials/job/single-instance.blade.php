@if ($instance)
    @include('admin.jobs.partials.job.instance', [
        'instance' => $instance,
        'job' => $job
    ])
@else
    <tbody class="instanceBase"
        data-job-key="{{ $job['key'] }}"
        data-has-settings="{{ $job['settings'] !== false ? 'true' : 'false' }}"
        data-allow-multiple="false"
    >
        <tr>
            <td class="jobList-job" data-title="@lang('jobs.job')" colspan="3">
                <h2>@lang('jobs.'.$job['key'].'.title')</h2>
                <p>@lang('jobs.'.$job['key'].'.description')</p>

                @include('admin.jobs.partials.job.settings-modal-template', [ 'job' => $job ])
            </td>
            <td class="jobList-actions" data-title="@lang('jobs.actions')">
                <button class="btn btn--bright-accent add-instance enable-job">Enable Job</button>
            </td>
        </tr>
    </tbody>
@endif