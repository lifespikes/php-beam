<?php

return [
    /* Where vite might place assets or other resources */
    'public_dir' => env('VITE_PUBLIC_DIR', public_path()),

    /* Where vite places compiled assets */
    'build_dir' => env('VITE_BUILD_DIR', public_path('build')),

    /* Primary entrypoint (script) for your Inertia app */
    'entry_point' => env('VITE_ENTRY_POINT', resource_path('js/App.js')),

    /* Merge with built-in php-beam aliases */
    'aliases' => [],

    /* Root Inertia view */
    'root_view' => env('VITE_INERTIA_VIEW', 'php-beam::app'),

    'vite' => [
        /* Port that the vite dev server is bound to */
        'bind_port' => env('VITE_PORT', 9000),

        /* URL/Host that the vite dev server is bound to (From a local network) */
        'bind_url' => env('VITE_URL', 'http://localhost'),

        /* Public URL to use when populating tags. Useful for when using tunnels */
        'public_url' => env('VITE_PUBLIC_URL', 'http://localhost:8080'),

        /* Vite SSL settings */
        'ssl_enabled' => env('VITE_SSL', false),
        'ssl_key' => env('VITE_SSL_KEY'),
        'ssl_cert' => env('VITE_SSL_CERT'),
    ]
];
