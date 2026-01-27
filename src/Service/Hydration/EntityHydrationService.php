<?php

namespace App\Service\Hydration;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\Mapping\ClassMetadata;

final readonly class EntityHydrationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private RelationHandler $relationHandler,
        private iterable $resolvers
    ) {}

    /**
     * @throws MappingException
     * @throws ORMException
     */
    public function hydrate(object $entity, array $data, bool $syncRelations = true): void
    {
        $metadata = $this->em->getClassMetadata($entity::class);

        foreach ($data as $field => $value) {
            $setter = 'set' . ucfirst($field);

            if (!method_exists($entity, $setter)) {
                continue;
            }

            $resolved = $this->resolveValue($metadata, $field, $value);
            $entity->$setter($resolved);

            if($syncRelations) $this->relationHandler->handleBidirectionalRelation($entity, $resolved);
        }
    }
    private function resolveValue(ClassMetadata $metadata, string $field, mixed $value): mixed
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->supports($metadata, $field, $value)) {
                return $resolver->resolve($metadata, $field, $value);
            }
        }

        return $value;
    }
}
