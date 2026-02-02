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
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Random\RandomException;

readonly class RefreshTokenService
{
    public function __construct(
        private RefreshTokenRepository  $refreshTokenRepository,
        private EntityManagerInterface $em,
        private JwtManager $jwtManager,
    ) {}

    /**
     * @throws TokenGenerationException
     */
    public function createRefreshToken(User $user): RefreshToken
    {
        try {
            $refreshToken = new RefreshToken();
            $refreshToken->setToken(bin2hex(random_bytes(64)));
            $refreshToken->setUser($user);

            return $refreshToken;
        } catch (RandomException) {
            throw new TokenGenerationException();
        }
    }

    /**
     * @throws RefreshTokenNotFoundException
     * @throws RefreshTokenExpiredException
     */
    public function validateToken(string $refreshTokenData): User
    {
        $token = $this->refreshTokenRepository->findOneBy(['token' => $refreshTokenData]);

        if (!$token) {
            throw new RefreshTokenNotFoundException();
        }

        if ($this->refreshTokenRepository->isExpired($token)) {
            throw new RefreshTokenExpiredException();
        }

        return $token->getUser();
    }

    public function rotateRefreshToken(RefreshTokenDto $dto): array
    {
        return $this->em->wrapInTransaction(
            function () use ($dto) {
                $user = $this->validateToken($dto->refreshToken);

                $newRefreshToken = $this->createRefreshToken($user);
                $accessToken = $this->jwtManager->create($user);


                $this->removeToken($dto->refreshToken);
                $this->em->persist($newRefreshToken);
                $this->em->flush();

                return [
                    'refreshToken' => $newRefreshToken->getToken(),
                    'accessToken'  => $accessToken,
                ];
            }
        );
    }
    public function removeToken(string $refreshTokenData): void
    {
        $token = $this->refreshTokenRepository->findOneBy(['token' => $refreshTokenData]);

        if ($token) {
            $token->getUser()->removeRefreshToken($token);
            $this->em->remove($token);
        }
    }
}
