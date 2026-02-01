<?php

namespace App\Service\Auth;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginService
{

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}
    public function login($dto): JsonResponse
    {
        $identifier = $dto->identifier;
        $criteria = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? ['email' => $identifier] : ['username' => $identifier];
        $user = $this->userRepository->findOneBy($criteria);

        if(!$user) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        if($user->getPassword() !== $this->passwordHasher->hashPassword($user, $dto->password)) {
            return new JsonResponse(['message' => 'Invalid credentials'], 401);
        }


        return new JsonResponse(
            [
                'token' => $user->generateAuthToken()
            ]
        );
    }
}
