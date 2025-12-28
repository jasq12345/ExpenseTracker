<?php

namespace App\Service;

use ReflectionClass;
use ReflectionException;

class EntityMetadataService
{
    public function getShortName(string $entityClass): string
    {
        try {
            return new ReflectionClass($entityClass)->getShortName();
        } catch (ReflectionException) {
            throw new \InvalidArgumentException(
                sprintf('Invalid entity class "%s"', $entityClass)
            );
        }
    }
}
