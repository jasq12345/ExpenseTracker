<?php

namespace App\Service\Hydration\Resolvers;

use App\Service\Hydration\Resolvers\HydrationResolverInterface;
use DateMalformedStringException;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\Mapping\ClassMetadata;

class DatetimeResolver implements HydrationResolverInterface
{
    /**
     * @throws MappingException
     */
    public function supports(ClassMetadata $metadata, string $field, mixed $value): bool
    {
        if (!$metadata->hasField($field) || !is_string($value)) {
            return false;
        }

        $fieldMapping = $metadata->getFieldMapping($field);
        $type = $fieldMapping['type'] ?? null;

        return $type === Types::DATETIME_MUTABLE || $type === Types::DATETIME_IMMUTABLE;
    }

    /**
     * @throws MappingException
     * @throws DateMalformedStringException
     */
    public function resolve(ClassMetadata $metadata, string $field, mixed $value): mixed
    {
        if ($value instanceof DateTimeInterface) {
            return $value;
        }

        if($value === null) return null;

        $fieldMapping = $metadata->getFieldMapping($field);
        $type = $fieldMapping['type'];

        try{
            return match ($type) {
                Types::DATETIME_MUTABLE => new DateTime($value),
                Types::DATETIME_IMMUTABLE => new DateTimeImmutable($value),
                default => $value,
            };
        } catch (DateMalformedStringException $e){
            throw new DateMalformedStringException($e->getMessage());
        }
    }
}
