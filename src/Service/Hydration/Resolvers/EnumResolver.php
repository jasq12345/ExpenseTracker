<?php

namespace App\Service\Hydration\Resolvers;

use App\Exception\Auth\EnumInvalidValueException;

use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\Mapping\ClassMetadata;

class EnumResolver implements ValueResolversInterface
{
    /**
     * @throws MappingException
     */
    public function supports(ClassMetadata $metadata, string $field, mixed $value): bool
    {
        if(!$metadata->hasField($field)) return false;

        $mapping = $metadata->getFieldMapping($field);

        return isset($mapping['enumType']);
    }

    /**
     * @throws MappingException
     */
    public function resolve(ClassMetadata $metadata, string $field, mixed $value): mixed
    {
        $mapping = $metadata->getFieldMapping($field);
        $enumClass = $mapping['enumType'];

        if ($value === null) {
            return null;
        }

        try {
            $normalizedValue = is_string($value) ? strtolower($value) : $value;
            return $enumClass::from($normalizedValue);
        } catch (EnumInvalidValueException) {
            throw new EnumInvalidValueException($field);
        }
    }

}
