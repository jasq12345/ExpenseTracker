<?php
namespace App\Service;

use App\Entity\User;
use App\Service\Validation\UserValidator;


readonly class MutationEntityService
{
    public function __construct(
        private EntityHydrationService $hydration,
        private UserValidator $validator
    ) {}

    public function mutateAndValidate(array $data, object $entity): array
    {
        $this->hydration->hydrateEntity($data, $entity);

        if ($entity instanceof User && isset($data['password'])){
            $entity->setPassword(
                $this->validator->hashPassword($data['password'], $entity)
            );
        }

        return $this->validator->validateData($entity);
    }
}
