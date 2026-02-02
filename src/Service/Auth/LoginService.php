<?php

namespace App\Service\Auth;

use App\Exception\Auth\TokenGenerationException;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class  LoginService
{

    public function __construct(
        private UserRepository              $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private RefreshTokenManager         $refreshTokenManager,
    ) {}

    /**
     * @throws TokenGenerationException
     */
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

        list($newRefreshToken, $accessToken) = $this->refreshTokenManager->newTokens($user);

        return new JsonResponse([
            'refreshToken' => $newRefreshToken->getToken(),
            'accessToken' => $accessToken,
        ]);
    }
}
