<?php

namespace Tests;

use ReflectionClass;

trait HasReflectiveTrait
{
    /**
     * Calls inaccessible method using reflection.
     *
     * @param object $object
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    protected function callInaccessibleMethod($object, string $method, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $reflectionMethod = $reflection->getMethod($method);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invokeArgs($object, $parameters);
    }

    /**
     * Gets value of an inaccessible property using reflection.
     *
     * @param object $object
     * @param string $property
     * @return mixed
     */
    protected function getInaccessibleProperty($object, string $property)
    {
        $reflection = new ReflectionClass(get_class($object));
        $reflectionProperty = $reflection->getProperty($property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }

    /**
     * Sets value of an inaccessible property using reflection.
     *
     * @param object $object
     * @param string $property
     * @param mixed $value
     * @return void
     */
    protected function setInaccessibleProperty($object, string $property, $value): void
    {
        $reflection = new ReflectionClass(get_class($object));
        $reflectionProperty = $reflection->getProperty($property);
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($object, $value);
    }
}
