<?php

namespace App\Entity;

use App\Repository\RefreshTokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: RefreshTokenRepository::class)]
class RefreshToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['refreshToken:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 128, unique: true)]
    #[Groups(['refreshToken:read'])]
    private ?string $token = null;

    #[ORM\Column]
    #[Groups(['refreshToken:read'])]
    private \DateTimeImmutable $expiresAt;

    #[ORM\ManyToOne(inversedBy: 'refreshTokens')]
    #[ORM\JoinColumn(name: "user_id", nullable: false)]
    #[Groups(['refreshToken:read'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->expiresAt = new \DateTimeImmutable('+30 days');
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
