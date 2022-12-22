<?php

namespace LifeSpikes\PhpBeam\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command as BaseCommand;

class Command extends BaseCommand
{
    protected function getStubContent(string $filename): string
    {
        return app(Filesystem::class)->get(realpath(__DIR__."/../../resources/stubs/$filename"));
    }
}
