<?php

namespace App\Http\Middleware;

use Closure;

class NotTopBanana extends Grounded
{

    protected $configKey = 'not-top-banana';
    protected $viewName = 'not-top-banana';
    protected $requestKey = 'sorry';

}
