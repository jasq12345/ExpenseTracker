<?php

namespace App\Service\Validation;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class UserValidator implements AppValidatorInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private readonly ValidatorInterface $validator) {}
    public function hashPassword(string $plainPassword, User $user): string
    {
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }

    public function validateData($data): array
    {
        $errors = $this->validator->validate($data);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $errorMessages;
        }
        return [];
    }
}
