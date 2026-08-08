<?php

namespace App\Http\Controllers;

use App\Models\ActiveSite;
use App\Models\JobInstance;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Throwable;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ActiveSite $site
     * @return View
     */
    public function index(ActiveSite $site): View
    {
        $jobs = config('jobs');
        $instances = $site->jobs()->with(['logs' => function($query) {
            return $query->orderBy('created_at', 'desc')->limit(3);
        }])->get();
        $instances = $instances->groupBy('job')->toArray();

        // dump($jobs, $instances);

        return view('admin.jobs.list', compact('jobs', 'instances'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $jobData = config('jobs.' . $input['jobKey']);
        unset($input['jobKey']);

        // check allowMultiple and existing
        if ($jobData['allowMultiple'] === false) {
            $existing = JobInstance::where('job', $jobData['job'])->first();
            if ($existing) {
                return response()->json([
                    'errorType' => 'multiple',
                    'message' => __('jobs.errors.multipleInstances')
                ], 422);
            }
        }

        // if it has settings, validate
        if ($jobData['settings'] !== false && method_exists($jobData['job'], 'validateSettings')) {
            $valid = call_user_func([$jobData['job'], 'validateSettings'], $input);
            if ($valid !== true) {
                return response()->json([
                    'errorType' => 'validation',
                    'message' => is_string($valid) ? $valid : __('jobs.errors.validation')
                ], 422);
            }
        }

        try {
            // create a new instance and fill settings
            // return JSON with a key of HTML of instance view
            $instance = new JobInstance();
            $instance->job = $jobData['job'];

            if ($jobData['settings'] !== false) {
                $instance->settings = $input;
            }

            $instance->enabled = $jobData['allowAutoRun'];

            $instance->saveOrFail();

            $html = view('admin.jobs.partials.job.instance', [
                'instance' => $instance,
                'job' => $jobData
            ])->render();

            return response()->json([
                'html' => $html
            ]);

        } catch (Throwable $exception) {
            return \response()->json([
                'errorType' => 'server',
                'message' => __('jobs.errors.saving')
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param JobInstance $jobInstance
     * @return JsonResponse
     */
    public function update(Request $request, JobInstance $jobInstance): JsonResponse
    {
        $jobData = config('jobs.'. $jobInstance->job::KEY);

        // if it has settings, validate
        if ($request->has('settings') && method_exists($jobData['job'], 'validateSettings')) {
            $valid = call_user_func([$jobData['job'], 'validateSettings'], $request->input('settings'));
            if ($valid !== true) {
                return response()->json([
                    'errorType' => 'validation',
                    'message' => is_string($valid) ? $valid : __('jobs.errors.validation')
                ], 422);
            }
        }

        $props = [
            'enabled' => 'enabled',
            'settings' => 'settings',
        ];
        foreach($props as $requestProp => $modelProp) {
            if ($request->has($requestProp)) {
                $jobInstance[$modelProp] = $request->input($requestProp);
            }
        }

        try {
            $jobInstance->saveOrFail();

            $rsp = [
                'instance' => $jobInstance
            ];

            return \response()->json($rsp);

        } catch (Throwable $exception) {
            return \response()->json([
                'errorType' => 'server',
                'message' => __('jobs.errors.saving')
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param JobInstance $jobInstance
     * @return JsonResponse
     */
    public function destroy(JobInstance $jobInstance): JsonResponse
    {
        $jobData = config('jobs.'. $jobInstance->job::KEY);

        try {
            $jobInstance->delete();

            if ($jobData['allowMultiple']) {
                $rsp = [
                    'action' => 'remove'
                ];
            } else {
                $rsp = [
                    'action' => 'replace',
                    'html' => view('admin.jobs.partials.job.single-instance', [
                        'job' => $jobData,
                        'instance' => null
                    ])->render()
                ];
            }

            return response()->json($rsp);

        } catch (Throwable $exception) {
            return response()->json([
                'errorType' => 'server',
                'message' => __('jobs.errors.saving')
            ], 500);
        }
    }

    /**
     * @param string $instanceId
     * @return JsonResponse
     * @throws Throwable
     */
    public function run(string $instanceId): JsonResponse
    {
        try {
            /**
             * @var JobInstance $jobInstance
             */
            $jobInstance = JobInstance::findOrFail($instanceId);
            $exitCode = $jobInstance->runCommand();

            $jobInstance->load(['logs' => function($query) {
                return $query->orderBy('created_at', 'desc')->limit(3);
            }])->get();

            $html = view('admin.jobs.partials.job.instance', [
                'job' => config('jobs.'. $jobInstance->job::KEY),
                'instance' => $jobInstance
            ])->render();

            $log = $jobInstance->logs[0];
            return response()->json([
                'status' => $log->state,
                'exitCode' => $exitCode,
                'output' => $log->output,
                'html' => $html
            ]);

        } catch (Exception $exception) {
            return response()->json([
                'errorType' => 'server',
                'message' => __('jobs.errors.saving')
            ], 500);
        }
    }
}
