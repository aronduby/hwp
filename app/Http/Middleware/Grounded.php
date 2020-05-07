<?php

namespace App\Http\Middleware;

use Closure;

class Grounded
{

    protected $configKey = 'grounded';
    protected $viewName = 'grounded';
    protected $requestKey = 'please';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (
            $request->query($this->requestKey, false) === false
            && in_array($request->player->name_key, config($this->configKey))
        ) {
            return response(view($this->viewName, ['player' => $request->player]));
        } else {
            return $next($request);
        }
    }
}
