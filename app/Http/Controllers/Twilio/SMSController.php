<?php

namespace App\Http\Controllers\Twilio;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Twilio\TwiML\MessagingResponse;

class SMSController extends Controller
{

    public function incoming(Request $request)
    {
        // catch the opt-in/out updates
        if ($request->has('OptOutType')) {
            switch ($request->get('OptOutType')) {
                case 'START':
                    $msg = $this->subscribe($request->get('From'), $request->get('Body'));
                    return $this->basicResponse($msg);

                case 'STOP':
                    $this->unsubscribe($request->get('From'));
                    return $this->basicResponse();

                case 'HELP':
                    return $this->basicResponse();
            }
        }

        // Just say we don't know how to respond right now
        return $this->basicResponse("Sorry, but I don't know how to handle that. Reply HELP for more information.");
    }

    public function subscribe(string $phone, string $type)
    {
        $message = null;

        switch (strtoupper($type)) {
            case Subscription::TYPE_ALL:
                $subType = Subscription::TYPE_QUARTERS;
                $message = "Subscriptions for ALL updates are currently reserved for grandparents or those with issues that prevent them from using push notifications, so we signed you up for QUARTERS instead. If you believe you qualify, touch base with Duby and he'll get it updated. Otherwise, do nothing and enjoy the updates at the end of the quarters, or head to the homepage to subscribe through push notifications.";
                break;

            case Subscription::TYPE_FINAL:
                $subType = Subscription::TYPE_FINAL;
                break;

            case Subscription::TYPE_QUARTERS:
            default:
                $subType = Subscription::TYPE_QUARTERS;
                break;
        }

        $sub = Subscription::firstOrNew(['phone' => $phone]);
        $sub->type = $subType;

        if ($sub->save()) {
            return $message;
        } else {
            return false;
        }
    }

    public function unsubscribe($phone)
    {
        return Subscription::where('phone', $phone)->delete();
    }

    protected function basicResponse($message = null) {
        $rsp = new MessagingResponse();
        if ($message) {
            $rsp->message($message);
        }

        return \Response::make($rsp, '200')->header('Content-Type', 'text/xml');
    }

}
