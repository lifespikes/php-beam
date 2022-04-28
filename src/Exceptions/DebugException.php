<?php
/*
 * PayrollGoat - HCM Software built on the Zeal Payroll API
 *
 * Copyright (c) LifeSpikes, LLC. 2022.
 *
 * Private license: Not to be distributed, modified, or otherwise shared without prior authorization from LifeSpikes, or by its contractually-bound customer upon delivery or release of IP.
 */

namespace LifeSpikes\PhpBeam\Exceptions;

use Exception;
use Spatie\LaravelIgnition\Facades\Flare;

class DebugException extends Exception
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
