<?php

namespace App\Http\Controllers\FirebaseCloudMessaging;

use App\Models\ActiveSite;
use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Kreait\Firebase\Messaging;

class Subscription extends Controller
{
    /**
     * @var ActiveSite $site
     */
    protected $site;

    /**
     * @var Messaging $messaging
     */
    protected $messaging;

    /**
     * @param ActiveSite $site
     */
    public function __construct(ActiveSite $site, Messaging $messaging)
    {
        $this->site = $site;
        $this->messaging = $messaging;
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required'
        ]);

        $token = $request->get('token');

        // if the token already exists don't bother
        $existing = PushSubscription::where('token', '=', $token)->first();
        if ($existing) {
            return response()->json(true);
        }

        $model = new PushSubscription(['token' => $token, 'site_id' => $this->site->id]);
        $model->saveOrFail();

        // now subscribe it to the site's topic
        $topic = 'site.'.$this->site->id;
        $topicResult = $this->messaging->subscribeToTopic($topic, $token);

        return response()->json($topicResult);
    }

    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required'
        ]);

        $token = $request->get('token');
        $deletedRows = PushSubscription::where('token', '=', $token)->delete();

        $topic = 'site.'.$this->site->id;
        $topicResult = $this->messaging->unsubscribeFromTopic($topic, $token);

        return response()->json([
            'deletedRows' => $deletedRows,
            'topicResult' => $topicResult
        ]);
    }
}
