<?php

use Webmozart\Assert\Assert;

/**
 * Support functions for the PHP Beam library.
 *
 * Just a collection of what is essentially useful code
 * snippets for day-to-day development.
 */

if (!function_exists('stub_path')) {
    function purge_null(array $array): array
    {
        return collect($array)
            ->filter(fn ($value) => !is_null($value))
            ->map(fn ($value) => is_array($value) ? purge_null($value) : $value)
            ->toArray();
    }
}

/** Type checking functions */

if (!function_exists('assert_array')) {
    /** @return array<mixed> */
    function assert_array(mixed $i): array
    {
        Assert::isArray($i);

        return $i;
    }
}

if (!function_exists('assert_string')) {
    function assert_string(mixed $i): string
    {
        Assert::string($i);

        return $i;
    }
}

if (!function_exists('assert_instance')) {
    /**
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    function assert_instance(mixed $i, string $class): object
    {
        Assert::isInstanceOf($i, $class);

        return $i;
    }
}

if (!function_exists('assert_float')) {
    function assert_float(mixed $i): float
    {
        Assert::float($i);

        return $i;
    }
}
