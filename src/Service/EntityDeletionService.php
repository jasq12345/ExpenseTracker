<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use ReflectionClass;

readonly class EntityDeletionService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function deleteRelations(object $entity): void
    {
        $metadata = $this->em->getClassMetadata($entity::class);
        foreach ($metadata->getAssociationMappings() as $mapping) {
            if ($mapping['type'] !== ClassMetadata::MANY_TO_ONE) {
                continue;
            }

            $field = $mapping['fieldName'];
            $getter = 'get' . ucfirst($field);

            $relatedEntity = $entity->$getter();

            $remover = 'remove' . new ReflectionClass($entity)->getShortName();

            $relatedEntity->$remover($entity);

        }
    }
}
