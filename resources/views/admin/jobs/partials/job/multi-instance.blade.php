<tbody class="instanceBase"
    data-job-key="{{ $job['key'] }}"
    data-has-settings="{{ $job['settings'] !== false ? 'true' : 'false' }}"
    data-allow-multiple="true"
>
    <tr>
        <td class="jobList-job" data-title="@lang('jobs.job')" colspan="3">
            <h2>@lang('jobs.'.$job['key'].'.title')</h2>
            <p>@lang('jobs.'.$job['key'].'.description')</p>

            @include('admin.jobs.partials.job.settings-modal-template', [ 'job' => $job ])
        </td>
        <td class="jobList-actions" data-title="@lang('jobs.actions')">
            <button class="btn btn--bright-accent add-instance"> <i class="fa fa-plus-circle"></i> <span>Add Instance</span></button>
        </td>
    </tr>
</tbody>

@foreach($instances as $instance)
    @include('admin.jobs.partials.job.instance', [
        'instance' => $instance,
        'job' => $job
    ])
@endforeach