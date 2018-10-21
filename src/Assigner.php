<?php

namespace Assigner;


use Illuminate\Support\Str;

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

            if ($this->isCollection($this->$property) && $this->canBeAssigned($value)) {
                $this->assignCollection($property, $value);
                continue;
            }

            if ($this->isAssignable($this->$property) && $this->canBeAssigned($value)) {
                $this->$property->assign($value);
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
        $item = $this->$property->create();

        //we fill data as is if collection item is not assignable
        if (!$this->isAssignable($item)) {
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
     * @param string $property   - object property to be set
     * @param string|null $class - type of collection item
     */
    private function initCollection(string $property, string $class = null): void
    {
        $this->$property = new Collection();
        $this->$property->macroObject('create', function () use ($class) {
            return null === $class ? null : new $class;
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