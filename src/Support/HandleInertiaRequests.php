<?php

namespace LifeSpikes\PhpBeam\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'php-beam::app';

    public function __construct()
    {
        $this->rootView = Config::get('php-beam.root_view');
    }

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            //
        ]);
    }
}
