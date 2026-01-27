<?php

namespace App\Service\Hydration\Resolvers;

use App\Exception\Auth\AssociationInvalidValueException;
use App\Exception\Auth\AssociationNullException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\Mapping\ClassMetadata;

readonly class RelationResolver implements ValueResolversInterface
{

    public function __construct(private EntityManagerInterface $em) {}
    public function supports(ClassMetadata $metadata, string $field, mixed $value): bool
    {
        return $metadata->hasAssociation($field);
    }

    /**
     * @throws MappingException
     * @throws ORMException
     * @throws AssociationNullException
     * @throws AssociationInvalidValueException
     */
    public function resolve(ClassMetadata $metadata, string $field, mixed $value): mixed
    {
        $mapping = $metadata->getAssociationMapping($field);
        $target = $mapping['targetEntity'];
        $isNullable = $mapping['joinColumns'][0]['nullable'] ?? false;


        if ($value === null) {
            if (!$isNullable) throw new AssociationNullException($field);
            return null;
        }

        if (!is_int($value) && !is_string($value)) {
            throw new AssociationInvalidValueException($field);
        }

        $id = is_string($value) ? (int) $value : $value;
        return $this->em->getReference($target, $id);
    }
}
