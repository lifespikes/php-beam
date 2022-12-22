<?php

namespace LifeSpikes\PhpBeam\Inertia\Config;

use Illuminate\Http\Request;

interface BeamConfig
{
    public function version(Request $request): ?string;

    public function share(Request $request): array;
}
