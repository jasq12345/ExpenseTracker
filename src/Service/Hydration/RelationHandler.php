<?php

namespace App\Service\Hydration;

use ReflectionClass;

final class RelationHandler
{
    private ?string $adder = null;
    private array $classNameCache = [];

    /**
     * Handles bidirectional relation between $entity and $related.
     * Supports both single object and iterable collections.
     */
    public function handleBidirectionalRelation(object $entity, mixed $related): void
    {
        if ($related === null) {
            return;
        }

        if (is_iterable($related)) {
            foreach ($related as $rel) {
                $this->attachEntity($entity, $rel);
            }
        } elseif (is_object($related)) {
            $this->attachEntity($entity, $related);
        }
    }

    /**
     * Attaches $entity to the related object using its adder method.
     */
    private function attachEntity(object $entity, object $related): void
    {
        $adder = $this->getAdder($entity);

        if (method_exists($related, $adder)) {
            $related->$adder($entity);
        }
    }

    /**
     * Returns the adder method name, optionally caching the short name.
     */
    private function getAdder(object $entity): string
    {
        if ($this->adder !== null) {
            return $this->adder;
        }

        $class = $entity::class;

        if (!isset($this->classNameCache[$class])) {
            $this->classNameCache[$class] = new ReflectionClass($entity)->getShortName();
        }

        return 'add' . $this->classNameCache[$class];
    }
}
