<?php

namespace LifeSpikes\PhpBeam\Support;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use LifeSpikes\PhpBeam\Support\Inertia\BeamConfig;
use LifeSpikes\PhpBeam\Support\Inertia\BeamConfigFactory;
use LifeSpikes\PhpBeam\Support\Inertia\Middleware;

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
