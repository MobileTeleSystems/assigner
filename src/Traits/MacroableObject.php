<?php

namespace Assigner\Traits;


use Closure;
use BadMethodCallException;

/**
 * Trait MacroableObject
 * @package Assigner\Traits
 */
trait MacroableObject
{
    /**
     * The registered string macros.
     *
     * @var array
     */
    private $objectMacros = [];

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
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (!$this->hasMacroObject($method)) {
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist.', static::class, $method
            ));
        }

        $macro = $this->objectMacros[$method];

        if ($macro instanceof Closure) {
            return call_user_func_array($macro->bindTo($this, static::class), $parameters);
        }

        return call_user_func_array($macro, $parameters);
    }
}