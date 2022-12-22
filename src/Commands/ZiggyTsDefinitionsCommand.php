<?php

namespace LifeSpikes\PhpBeam\Commands;

use Tightenco\Ziggy\Ziggy;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Stringable;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;

use function dirname;

use const JSON_THROW_ON_ERROR;

class ZiggyTsDefinitionsCommand extends Command
{
    protected $signature = 'ziggy:ts-definitions
    {path=./packages/frontend/src/types/shims-ziggy.d.ts : Path to the generated TypeScript file.}';

    protected $description = 'Generate TS definitions for ziggy';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }
    public function handle(): void
    {
        /** @var string $argumentPath */
        $argumentPath = $this->argument('path');
        $path = base_path($argumentPath);
        $generatedRoutes = $this->generate();

        $this->makeDirectory($path);

        $raw = Str::replace(
            '{{ROUTES}}',
            $generatedRoutes,
            trim($this->getStubContent('ziggy-ts.stub'), "\n")
        );

        if (false !== $this->files->put($path, $raw)) {
            $this->info('File generated!');
        }
    }

    protected function makeDirectory(string $path): string
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true, true);
        }

        return $path;
    }

    private function generate(): string
    {
        $ziggy = (new Ziggy(false, null));

        /** @var array<string, string> $ziggyRoutes */
        $ziggyRoutes = $ziggy->toArray()['routes'];

        /** @var Collection<int, array{methods?: string, uri: string}> $ziggyRoutesCollection */
        $ziggyRoutesCollection = collect($ziggyRoutes);

        return $ziggyRoutesCollection
            ->map(function ($route, $key) {
                $methods = json_encode($route['methods'] ?? [], JSON_THROW_ON_ERROR);
                $methods = str_replace(['"', "','"], ["'", "', '"], $methods);
                \Safe\preg_match_all('/{\K[^}]*(?=})/m', $route['uri'], $bindings);

                /** @var array<int, string> $bindings */
                $bindings = Arr::flatten($bindings);

                /** @var Collection<int, string> $bindings */
                $bindings = collect($bindings);

                $joinedBindings = $bindings->map(fn ($i) => "'$i'")->join(' | ');

                $stringBindings = (new Stringable($joinedBindings))
                    ->replace('"', '')
                    ->toString();

                $stringBindings = strlen($stringBindings) < 1 ? 'string' : $stringBindings;

                $key = !ctype_alnum($key) ? "'$key'" : $key;

                return sprintf(
                    "  %s: {\n    uri: '%s';\n    methods: %s;\n    bindings: %s; \n  };",
                    $key,
                    $route['uri'],
                    $methods,
                    $stringBindings
                );
            })
            ->join("\n");
    }
}
