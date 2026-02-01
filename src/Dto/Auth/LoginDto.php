<?php

namespace App\Dto\Auth;

use Symfony\Component\Validator\Constraints as Assert;

class LoginDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $identifier,
        #[Assert\NotBlank]
        public string $password
    ) {}
}
