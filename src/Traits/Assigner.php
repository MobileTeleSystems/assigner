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
            $property = $this->transformInputProperty($key);

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
     * @param string $collection
     * @param array $values
     */
    private function assignCollection(string $collection, array $values): void
    {
        $this->isAssignable($this->$collection->createItem()) ?
            $this->fillCollectionWithAssignable($collection, $values) :
            $this->fillCollection($collection, $values);
    }

    /**
     * @param string $collection
     * @param array $values
     */
    private function fillCollection(string $collection, array $values): void
    {
        foreach ($values as $key => $value) {
            $this->$collection->put($key, $value);
        }
    }

    /**
     * @param string $collection
     * @param array $values
     */
    private function fillCollectionWithAssignable(string $collection, array $values): void
    {
        foreach ($values as $key => $value) {
            if (!$this->canBeAssigned($value)) {
                continue;
            }
            /** @var Assignable $item */
            $item = $this->$collection->createItem();
            $item->assign($value);

            $this->$collection->put($key, $item);
        }
    }

    /**
     * @param string $collection
     * @param string|null $class
     *
     * @throws InvalidArgumentException
     */
    private function initCollection(string $collection, string $class = null): void
    {
        $this->$collection = new Collection();
        $this->$collection->macroObject('createItem',
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

    /**
     * @param $value
     * @return string
     */
    private function transformInputProperty($value): string
    {
        return Str::camel($value);
    }
}
