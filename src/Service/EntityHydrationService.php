<?php

namespace App\Service;

class EntityHydrationService
{
    public function hydrateEntity($data, $entity): void
    {
        foreach ($data as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($entity, $setter)) {
                $entity->$setter($value);
            }
        }
    }
}
