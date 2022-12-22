<?php

namespace LifeSpikes\PhpBeam\Inertia;

use Illuminate\Http\Request;
use Illuminate\Contracts\Config\Repository;
use Inertia\Middleware as InertiaMiddleware;
use LifeSpikes\PhpBeam\Inertia\Config\BeamConfig;

class Middleware extends InertiaMiddleware
{
    public function __construct(Repository $config, public BeamConfig $beam)
    {
        $this->rootView = $config->get('php-beam.root_view', 'php-beam::app');
    }

    public function version(Request $request): ?string
    {
        return $this->beam->version($request) ?? parent::version($request);
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            ...$this->beam->share($request),
        ];
    }
}
