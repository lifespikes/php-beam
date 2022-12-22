<?php

namespace LifeSpikes\PhpBeam\LaravelData;

abstract class Data extends \Spatie\LaravelData\Data
{
    /** @return mixed[] */
    public function get(string ...$keys): array
    {
        $buffer = [];
        $props = $this->toArray();

        foreach ($keys as $key) {
            if (!isset($props[$key])) {
                throw new \RuntimeException("$key does not exist on ".__CLASS__);
            }

            $buffer[] = $props[$key];
        }

        return $buffer;
    }
}
