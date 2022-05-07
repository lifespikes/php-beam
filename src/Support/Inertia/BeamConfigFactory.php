<?php

namespace LifeSpikes\PhpBeam\Support\Inertia;

use Closure;
use Illuminate\Http\Request;

class BeamConfigFactory
{
    /**
     * @param Closure|null $version
     * @param Closure|null $share
     * @return Closure A closure that returns a BeamConfig instance
     */
    public static function create(
        ?Closure $version = null,
        ?Closure $share = null,
    ): Closure {
        return function () use ($version, $share) {
            $instance = new class() implements BeamConfig {
                public ?Closure $version;
                public ?Closure $share;

                public function version(Request $request): ?string
                {
                    return ($version = $this->version) ? $version($request) : null;
                }

                public function share(Request $request): array
                {
                    return ($share = $this->share) ? $share($request) : [];
                }
            };

            $instance->version = $version;
            $instance->share = $share;

            return $instance;
        };
    }
}
