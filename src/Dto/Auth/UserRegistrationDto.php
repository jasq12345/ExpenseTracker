<?php

namespace App\Dto\Auth;

use Symfony\Component\Validator\Constraints as Assert;

final class UserRegistrationDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Username is required.')]
        #[Assert\Length(
            min: 3,
            max: 50,
            minMessage: 'Username must be at least {{ limit }} characters.',
            maxMessage: 'Username cannot exceed {{ limit }} characters.'
        )]
        public string $username,

        #[Assert\NotBlank(message: 'Email is required.')]
        #[Assert\Email(message: 'Email must be a valid email address.')]
        public string $email,

        #[Assert\NotBlank(message: 'Password is required.')]
        #[Assert\Length(
            min: 8,
            minMessage: 'Password must be at least {{ limit }} characters.'
        )]
        #[Assert\NotCompromisedPassword(message: 'This password has been leaked before, choose another one.')]
        #[Assert\Regex(
            pattern: '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*]).+$/',
            message: 'Password must contain uppercase, lowercase, number, and special character.'
        )]
        public string $password
    ) {}
}

