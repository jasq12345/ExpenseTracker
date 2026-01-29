<?php

namespace App\Dto\Auth;

use App\Validator\Constraint\StrongPassword;
use App\Validator\Constraint\UniqueEmail;
use App\Validator\Constraint\UniqueUsername;

final class UserRegistrationDto
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

