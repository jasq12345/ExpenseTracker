<?php

namespace App\Dto\Auth;

use Symfony\Component\Validator\Constraints as Assert;

class RefreshTokenDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $refreshToken
    ) {}
}
