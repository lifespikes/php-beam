<?php

namespace LifeSpikes\PhpBeam;

use Illuminate\Contracts\Config\Repository;
use LifeSpikes\PhpBeam\Laravel\ServiceProvider;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use LifeSpikes\PhpBeam\Commands\ZiggyTsDefinitionsCommand;

class PhpBeamServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/php-beam.php', 'php-beam');
        $this->loadViewsFrom(__DIR__.'/../views', 'php-beam');
        $this->createViteConfiguration();

        $this->commands([
            ZiggyTsDefinitionsCommand::class
        ]);
    }

    private function createViteConfiguration()
    {
        $this->mergeConfig('vite', ['merged' => 'config']);

        $this->mergeConfig('vite', function (Repository $config) {
            $appConfig = $config->get('php-beam');
            $generator = require __DIR__.'/../config/php-beam-vite.php';

            return $generator(
                publicDir: $appConfig['public_dir'],
                buildDir: $appConfig['build_dir'],
                entryPoint: $appConfig['entry_point'],
                viteBindUri: $appConfig['vite']['bind_url'],
                viteBindPort: $appConfig['vite']['bind_port'],
                vitePublicUri: $appConfig['vite']['public_url'],
                viteSslKey: $appConfig['vite']['ssl_key'],
                viteSslCert: $appConfig['vite']['ssl_cert'],
                aliases: $appConfig['aliases'],
            );
        });
    }
}
