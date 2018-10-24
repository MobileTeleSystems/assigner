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
        return $this->hasMacroObject($method) ?
            $this->objectMacrosCall($method, $parameters) :
            parent::__call($method, $parameters);
    }
}