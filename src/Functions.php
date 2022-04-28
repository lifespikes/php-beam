<?php

/**
 * Support functions for the PHP Beam library.
 *
 * Just a collection of what is essentially useful code
 * snippets for day-to-day development.
 */

function purge_null(array $array): array
{
    return collect($array)
        ->filter(fn ($value) => !is_null($value))
        ->map(fn ($value) => is_array($value) ? purge_null($value) : $value)
        ->toArray();
}
