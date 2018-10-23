<?php

namespace Assigner\Traits;


use Assigner\Collection;
use Assigner\Contracts\Assignable;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Trait Assigner
 * @package Assigner
 */
trait Assigner
{
    /**
     * @param array $data
     */
    public function assign(array $data): void
    {
        foreach ($data as $key => $value) {
            $property = Str::camel($key);

            if (!property_exists($this, $property)) {
                continue;
            }

            if ($this->isCollection($this->$property)) {
                if ($this->canBeAssigned($value)) {
                    $this->assignCollection($property, $value);
                }

                continue;
            }

            if ($this->isAssignable($this->$property)) {
                if ($this->canBeAssigned($value)) {
                    $this->$property->assign($value);
                }

                continue;
            }

            $this->$property = $value;
        }
    }

    /**
     * @param string $property
     * @param array $values
     */
    private function assignCollection(string $property, array $values): void
    {
        // item of Collection has no type, so we assign it as is
        if (null === $this->$property->create()) {
            foreach ($values as $key => $value) {
                $this->$property->put($key, $value);
            }

            return;
        }

        foreach ($values as $key => $value) {
            // skip broken data
            if (!$this->canBeAssigned($value)) {
                continue;
            }

            /** @var Assignable $item */
            $item = $this->$property->create();
            $item->assign($value);

            $this->$property->put($key, $item);
        }
    }

    /**
     * @param string $property
     * @param string|null $class
     *
     * @throws InvalidArgumentException
     */
    private function initCollection(string $property, string $class = null): void
    {
        $this->$property = new Collection();
        // here we macro current object with method create(),
        // that returns new item of Collection or null
        $this->$property->macroObject('create',
            function () use ($class): ?Assignable {
                if (null === $class) {
                    return null;
                }

                if (!class_exists($class)) {
                    throw new InvalidArgumentException(sprintf('Can not load class: %s', $class));
                }

                $item = new $class;

                if (!$item instanceof Assignable) {
                    throw new InvalidArgumentException(sprintf('%s must implement Assignable', $class));
                }

                return $item;
            });
    }

    /**
     * @param $object
     * @return bool
     */
    private function isCollection($object): bool
    {
        return $object instanceof Collection;
    }

    /**
     * @param $object
     * @return bool
     */
    private function isAssignable($object): bool
    {
        return $object instanceof Assignable;
    }

    /**
     * @param $value
     * @return bool
     */
    private function canBeAssigned($value): bool
    {
        return is_array($value);
    }
}