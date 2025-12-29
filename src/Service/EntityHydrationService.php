<?php

namespace App\Service;

use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\Mapping\ClassMetadata;
use InvalidArgumentException;
use ReflectionClass;
use Throwable;

final readonly class EntityHydrationService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /**
     * @throws MappingException
     * @throws ORMException
     */
    public function hydrate(object $entity, array $data): void
    {
        $metadata = $this->em->getClassMetadata($entity::class);

        foreach ($data as $field => $value) {
            $setter = 'set' . ucfirst($field);

            if (!method_exists($entity, $setter)) {
                continue;
            }

            $resolved = $this->resolveValue($metadata, $field, $value);
            $entity->$setter($resolved);

            $this->handleBidirectionalRelation($entity, $resolved);
        }
    }

    /**
     * @throws MappingException
     * @throws ORMException
     */
    private function resolveValue(ClassMetadata $metadata, string $field, mixed $value): mixed
    {
        // ASSOCIATIONS
        if ($metadata->hasAssociation($field)) {
            $target = $metadata->getAssociationTargetClass($field);

            if (is_array($value) && isset($value['id'])) {
                return $this->em->getReference($target, $value['id']);
            }

            if (is_int($value) || is_string($value)) {
                return $this->em->getReference($target, $value);
            }

            throw new InvalidArgumentException(
                sprintf('Invalid value for association "%s"', $field)
            );
        }

        if ($metadata->hasField($field)) {
            $mapping = $metadata->getFieldMapping($field);

            if (is_string($value)) {
                try {
                    return match ($mapping['type'] ?? null) {
                        Types::DATETIME_MUTABLE   => new DateTime($value),
                        Types::DATETIME_IMMUTABLE => new DateTimeImmutable($value),
                        default => isset($mapping['enumType'])
                            ? $mapping['enumType']::from(
                                $value ? strtolower($value) : $value
                            )
                            : $value,
                    };
                } catch (Throwable $e) {
                    throw new InvalidArgumentException(
                        sprintf('Invalid value for field "%s"', $field),
                        0,
                        $e
                    );
                }
            }
        }

        return $value;
    }

    private function handleBidirectionalRelation(object $entity, mixed $related): void
    {
        if (!is_object($related)) {
            return;
        }

        $adder = 'add' . new ReflectionClass($entity)->getShortName();

        if (method_exists($related, $adder)) {
            $related->$adder($entity);
        }
    }
}
