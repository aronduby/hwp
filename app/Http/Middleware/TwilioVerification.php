<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Twilio\Security\RequestValidator;

class TwilioVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->headers->has('HTTP_X_TWILIO_SIGNATURE')) {
            die('nope');
        }

        // Your auth token from twilio.com/user/account
        $token = config('services.twilio.token');

        // The X-Twilio-Signature header - in PHP this should be $_SERVER["HTTP_X_TWILIO_SIGNATURE"];
        $signature = $request->headers->get('HTTP_X_TWILIO_SIGNATURE');

        // Initialize the validator
        $validator = new RequestValidator($token);

        // The Twilio request URL. You may be able to retrieve this from $_SERVER['SCRIPT_URI']
        $url = $_SERVER['SCRIPT_URI'];

        // The post variables in the Twilio request. You may be able to use
        $postVars = $_POST;

        if ($validator->validate($signature, $url, $postVars)) {
            return $next($request);
        } else {
            die('nope');
        }
    }
}
