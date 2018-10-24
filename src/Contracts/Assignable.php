<?php

namespace Assigner\Contracts;


use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface Assignable
 * @package Assigner
 */
interface Assignable extends Arrayable
{
    /**
     * @param array $data
     */
    public function assign(array $data);
}