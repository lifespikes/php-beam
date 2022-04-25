<?php

return fn (
    ?string $publicDir = null,
    ?string $buildDir = null,
    ?string $entryPoint = null,
    ?string $viteBindUri = null,
    ?string $viteBindPort = null,
    ?string $vitePublicUri = null,
    ?string $viteSslKey = null,
    ?string $viteSslCert = null,
    ?array  $aliases = []
) => [
    'configs' => [
        'php-beam' => [
            'entrypoints' => [
                'paths' => [$entryPoint],
                'ignore' => '/\\.(d\\.ts|json)$/',
            ],
            'dev_server' => [
                'enabled' => true,
                'ping_timeout' => 1,
                'url' => "$viteBindUri:$viteBindPort",
                'key' => $viteSslKey,
                'cert' => $viteSslCert,
            ],
            'build_path' => $buildDir,
        ],
    ],

    'commands' => [
        'artisan' => [
            'vite:tsconfig'
        ]
    ],

    'aliases' => [
        '@php-beam' => '.' . str_replace(getcwd(), '', realpath(__DIR__ . '/../src/vite')),
        ...($aliases ?? [])
    ],

    'env_prefixes' => ['VITE_', 'MIX_', 'SCRIPT_'],

    'interfaces' => [
        'heartbeat_checker' => Innocenzi\Vite\HeartbeatCheckers\HttpHeartbeatChecker::class,
        'tag_generator' => Innocenzi\Vite\TagGenerators\CallbackTagGenerator::class,
        'entrypoints_finder' => Innocenzi\Vite\EntrypointsFinder\DefaultEntrypointsFinder::class,
    ],

    'uri' => $viteBindUri,
    'port' => $viteBindPort,
    'dev_url' => $vitePublicUri,
    'public_directory' => $publicDir,

    'default' => 'php-beam',
];
