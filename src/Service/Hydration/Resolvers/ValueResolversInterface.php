<?php

namespace App\Service\Hydration\Resolvers;

use Doctrine\Persistence\Mapping\ClassMetadata;

interface ValueResolversInterface
{
    public function resolve();
    public function supports(ClassMetadata $metadata, string $field, mixed $value): bool;
}
