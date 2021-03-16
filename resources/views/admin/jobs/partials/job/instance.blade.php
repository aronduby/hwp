@php
$latestLog = count($instance['logs']) ? $instance['logs'][0] : false;
$id = $job['job'] . '-'.$instance['id'];
@endphp

<tbody data-job="{{ $job['key'] }}" data-instance-id="{{ $instance['id'] }}">
    <tr>
        <td class="jobList-job" data-title="@lang('jobs.job')">
            @if($job['allowMultiple'] && $instance)
                <h2>{{ array_key_exists('title', $instance) ? $instance['settings']['title'] : __('jobs.untitledInstance') }} <small>@lang('jobs.'.$job['key'].'.title')</small></h2>
            @else
                <h2>@lang('jobs.'.$job['key'].'.title')</h2>
            @endif
            <p>@lang('jobs.'.$job['key'].'.description')</p>
        </td>
        <td class="jobList-status" data-title="@lang('jobs.status')">
            @if($latestLog)
                <i class="text--{{ $latestLog['state']}} @lang('jobs.icons.'.$latestLog['state'])" title="@lang('jobs.'.$latestLog['state'])"></i>
            @else
                <i class="text--warn @lang('jobs.icons.unknown')" title="@lang('jobs.unknown')"></i>
            @endif
        </td>
        <td class="jobList-autoRun" data-title="@lang('jobs.autoRun')">
            @if($job['allowAutoRun'] && !$job['disabled'])
                <label>
                    <input
                            id="{{ $id }}-toggler"
                            type="checkbox"
                            name="{{ $id }}-toggler"
                            class="toggler toggler--bright"
                            value="1"
                            {{ $instance['enabled'] ? 'checked' : '' }}
                    />
                    <label for="{{ $id }}-toggler"></label>
                </label>
            @else
                <span class="text--muted" title="auto-run not supported">&mdash;</span>
            @endif
        </td>
        <td class="jobList-actions" data-title="@lang('jobs.actions')">
            <button class="btn run-job" title="run job" @if($job['disabled']) disabled @endif>run job</button>
            @if($job['allowMultiple'])
                <button class="btn btn--danger delete-instance" title="delete job">&times;</button>
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="4" class="settingsAndLogs">
            <h3><a class="toggler"><i class="fa fa-plus-square"></i> @lang('jobs.settingsAndLogs')</a></h3>

            <div class="settingsAndLogs-holder">
                <hr>

                <div class="row">
                    <section class="settings col-lg-6">
                        <header>
                            <h3>Settings</h3>
                        </header>

                        <div class="instance-settings">
                            @include('admin.jobs.partials.settings.'.($job['settings'] ?: 'no-settings'), ['instance' => $instance])
                        </div>
                    </section>

                    <section class="logs col-lg-6">
                        <header>
                            <h3>Logs</h3>
                        </header>

                        <pre>@forelse($instance['logs'] as $log)<time datetime="{{$log['created_at']}}">@stamp(new \Carbon\Carbon($log['created_at']))</time>{{ trim($log['output'])}}<hr>@empty no log data yet @endforelse</pre>
                    </section>
                </div>


                <p class="text-align--center">
                    <a class="toggler collapse"><i class="fa fa-angle-double-up"></i> @lang('jobs.collapse') <i class="fa fa-angle-double-up"></i></a>
                </p>
            </div>
        </td>
    </tr>
</tbody>