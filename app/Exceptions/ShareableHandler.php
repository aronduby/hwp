<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Http\Response as LaravelResponse;
use Symfony\Component\HttpFoundation\Response;

class ShareableHandler extends Handler
{

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception|Throwable $e
     * @return LaravelResponse|Response
     * @throws Throwable
     */
    public function render($request, Exception|Throwable $e): LaravelResponse|Response
    {
        if (env('APP_DEBUG') != true) {
            return response()->view('shareables.error', [], 500);
        }

        return parent::render($request, $e);
    }
}
