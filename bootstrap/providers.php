<?php

use App\Providers\AppServiceProvider;
use App\Providers\DateDirectiveServiceProvider;
use App\Providers\MediaServiceProvider;
use App\Providers\MiscDirectiveServiceProvider;
use App\Providers\RouteDirectiveServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\TenantServiceProvider;

return [
    /**
     * Package Service Providers
     */
    NunoMazer\Samehouse\LandlordServiceProvider::class,

    /**
     * Application Service Providers
     */
    AppServiceProvider::class,
    DateDirectiveServiceProvider::class,
    TenantServiceProvider::class,
    RouteServiceProvider::class,
    RouteDirectiveServiceProvider::class,
    MiscDirectiveServiceProvider::class,

    // make sure this is after the tenant provider
    MediaServiceProvider::class,
];
