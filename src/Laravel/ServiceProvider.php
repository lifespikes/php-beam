<?php

namespace LifeSpikes\PhpBeam\Laravel;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * @param string $key
     * @param (callable(Repository $config): array)|array $configFactory
     * @return void
     * @throws BindingResolutionException
     */
    protected function mergeConfig(string $key, Closure|array $configFactory): void
    {
        if (!($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
            ($config = $this->app->make('config'))->set(
                $key,
                [...$config->get($key, []), ...is_array($configFactory) ? $configFactory : $configFactory($config)]
            );
        }
    }
}
