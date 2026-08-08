<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Grounded
{

    protected string $configKey = 'grounded';
    protected string $viewName = 'grounded';
    protected string $requestKey = 'please';

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
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
