<?php

namespace App\Security\Token;

use App\Dto\Auth\RefreshTokenDto;
use App\Entity\RefreshToken;
use App\Entity\User;
use App\Exception\Auth\RefreshTokenExpiredException;
use App\Exception\Auth\RefreshTokenNotFoundException;
use App\Exception\Auth\TokenGenerationException;
use App\Repository\RefreshTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Random\RandomException;

readonly class RefreshTokenService
{
    public function __construct(
        private RefreshTokenRepository  $refreshTokenRepository,
        private EntityManagerInterface $em,
        private JWTTokenManagerInterface $jwtManager,
    ) {}

    /**
     * @throws TokenGenerationException
     */
    public function createRefreshToken(User $user): RefreshToken
    {
        try {
            $token = new RefreshToken();
            $token->setToken(bin2hex(random_bytes(64)));
            $token->setUser($user);

            $this->em->persist($token);
            $this->em->flush();

            return $token;
        } catch (RandomException) {
            throw new TokenGenerationException();
        }
    }

    /**
     * @throws RefreshTokenExpiredException
     */
    private function validateToken(RefreshToken $token): User
    {

        if ($this->refreshTokenRepository->isExpired($token)) {
            throw new RefreshTokenExpiredException();
        }

        return $token->getUser();
    }

    /**
     * @throws RefreshTokenNotFoundException
     */
    private function getToken(string $token): ?RefreshToken
    {
        $token = $this->refreshTokenRepository->findOneBy(['token' => $token]);
        if (!$token) {
            throw new RefreshTokenNotFoundException();
        }
        return $token;
    }
    public function rotateRefreshToken(RefreshTokenDto $dto): array
    {
        return $this->em->wrapInTransaction(
            function () use ($dto) {
                $oldToken = $this->getToken($dto->refreshToken);
                $user = $this->validateToken($oldToken);

                $refreshToken = $this->createRefreshToken($user);
                $accessToken = $this->jwtManager->create($user);

                $this->removeToken($user, $oldToken);

                $this->em->flush();

                return [
                    'refreshToken' => $refreshToken->getToken(),
                    'accessToken'  => $accessToken,
                ];
            }
        );
    }
    private function removeToken(User $user, RefreshToken $token): void
    {
        $user->removeRefreshToken($token);
        $this->em->remove($token);
    }
}
