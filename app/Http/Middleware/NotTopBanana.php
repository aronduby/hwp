<?php

namespace App\Http\Middleware;

use Closure;

class NotTopBanana extends Grounded
{

    protected string $configKey = 'not-top-banana';
    protected string $viewName = 'not-top-banana';
    protected string $requestKey = 'sorry';

}
