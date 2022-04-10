<?php

namespace LifeSpikes\Livr;

use Config;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ViewFinderInterface;
use Inertia\Middleware as InertiaMiddleware;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Contracts\Container\BindingResolutionException;

class LivrServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/livr.php', 'livr');
        $this->loadViewsFrom(__DIR__ . '/../views', 'livr');
        $this->createViteConfiguration();
    }

    public function boot()
    {
        Inertia::setRootView(Config::get('livr.root_view'));
        Route::pushMiddlewareToGroup('web', InertiaMiddleware::class);
    }

    private function createViteConfiguration()
    {
        if (! ($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
            $config = $this->app->make('config');
            $appConfig = $config->get('livr');
            $generator = require __DIR__ . '/../config/livr-vite.php';

            $config->set('vite', array_merge(
                $config->get('vite', []),
                $generator(
                    publicDir: $appConfig['public_dir'],
                    buildDir: $appConfig['build_dir'],
                    entryPoint: $appConfig['entry_point'],
                    viteBindUri: $appConfig['vite']['bind_url'],
                    viteBindPort: $appConfig['vite']['bind_port'],
                    vitePublicUri: $appConfig['vite']['public_url'],
                    viteSslKey: $appConfig['vite']['ssl_key'],
                    viteSslCert: $appConfig['vite']['ssl_cert'],
                    aliases: $appConfig['aliases'],
                )
            ));
        }
    }
}
