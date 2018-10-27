<?php

namespace Assigner\Contracts;


/**
 * Interface Assignable
 * @package Assigner
 */
interface Assignable
{
    /**
     * @param array $data
     */
    public function assign(array $data);
}