<?php

namespace LifeSpikes\PhpBeam\Support\SimpleHttpClient\Exceptions;

use Spatie\LaravelIgnition\Facades\Flare;
use LifeSpikes\PhpBeam\Exceptions\DebugException;

class SimpleHttpClientException extends DebugException
{
    public array $context;

    public function setContext(mixed $context): self
    {
        $this->context = is_array($context) ?
            $context : ['context' => $context];

        Flare::context(
            'Exception Context',
            $this->context
        );

        return $this;
    }
}
