<?php

namespace LifeSpikes\PhpBeam\Facades;

use Closure;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use LifeSpikes\PhpBeam\Inertia\Middleware;
use LifeSpikes\PhpBeam\Inertia\Config\BeamConfig;
use LifeSpikes\PhpBeam\Inertia\Config\BeamConfigFactory;

class PhpBeam
{
    /**
     * Configure and run Inertia bindings packaged by
     * Beam.
     *
     * @param Closure|null $version Inertia Asset versions
     * @param Closure|null $share   Inertia shared data
     */
    public static function bindInertia(
        ?Closure $version = null,
        ?Closure $share = null
    ): void {
        app()->bindIf(
            BeamConfig::class,
            BeamConfigFactory::create($version, $share)
        );

        Inertia::setRootView(
            Config::get('php-beam.root_view')
        );

        Route::pushMiddlewareToGroup('web', Middleware::class);
    }
}
