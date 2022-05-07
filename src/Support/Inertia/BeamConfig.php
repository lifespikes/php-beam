<?php

namespace LifeSpikes\PhpBeam\Support\Inertia;

use Illuminate\Http\Request;

interface BeamConfig
{
    public function version(Request $request): ?string;

    public function share(Request $request): array;
}
