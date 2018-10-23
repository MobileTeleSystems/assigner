<?php

namespace Assigner;


use Assigner\Traits\MacroableObject;
use Illuminate\Support\Collection as Base;

/**
 * Class Collection
 * @package Assigner
 */
class Collection extends Base
{
    use MacroableObject {
        __call as objectMacrosCall;
    }

    /**
     * @inheritdoc
     */
    public function __call($method, $parameters)
    {
        if ($this->hasMacroObject($method)) {
            $this->objectMacrosCall($method, $parameters);
        } else {
            parent::__call($method, $parameters);
        }
    }
}