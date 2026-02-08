<?php

namespace App\Dto\Auth;

use App\Validator\Constraint\Register\StrongPassword;
use App\Validator\Constraint\Register\UniqueEmail;
use App\Validator\Constraint\Register\UniqueUsername;

final class RegisterDto
{
    public function __construct(
        #[UniqueUsername]
        public string $username,

        #[UniqueEmail]
        public string $email,

        #[StrongPassword]
        public string $password
    ) {}
}

