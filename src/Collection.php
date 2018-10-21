<?php

namespace Assigner;


use Closure;
use BadMethodCallException;
use Illuminate\Support\Collection as Base;

/**
 * Class Collection
 * @package Assigner
 */
class Collection extends Base
{
    /**
     * The registered string macros.
     *
     * @var array
     */
    protected $objectMacros = [];

    /**
     * Register a custom macro.
     *
     * @param  string $name
     * @param  object|callable  $macro
     *
     * @return void
     */
    public function macroObject(string $name, $macro): void
    {
        $this->objectMacros[$name] = $macro;
    }

    /**
     * Checks if macro is registered.
     *
     * @param  string  $name
     * @return bool
     */
    public function hasMacroObject(string $name): bool
    {
        return isset($this->objectMacros[$name]);
    }

    /**
     * @inheritdoc
     */
    public function __call($method, $parameters)
    {
        if ($this->hasMacroObject($method)) {
            return $this->run($this->objectMacros[$method], $parameters);
        }

        if (static::hasMacro($method)) {
            return $this->run(static::$macros[$method], $parameters);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }

    /**
     * @param $macro
     * @param $parameters
     * @return mixed
     */
    private function run($macro, $parameters)
    {
        if ($macro instanceof Closure) {
            return call_user_func_array($macro->bindTo($this, static::class), $parameters);
        }

        return call_user_func_array($macro, $parameters);
    }
}