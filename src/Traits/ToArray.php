<?php

namespace Assigner\Traits;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

/**
 * Trait ToArray
 * @package Assigner
 */
trait ToArray
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = [];

        foreach (get_object_vars($this) as $property => $value) {
            $key = Str::snake($property);
            $array[$key] = $value instanceof Arrayable ? $value->toArray() : $value;
        }

        return $array;
    }
}