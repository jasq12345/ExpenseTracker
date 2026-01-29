<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class StrongPassword extends Constraint
{
    public string $messageNotBlank = 'Password is required.';
    public string $messageTooShort= 'Password must be at least 8 characters long.';
    public string $messageNotCompromisedPassword= 'This password has been leaked before, choose another one.';
    public string $messageRegex = 'Password must contain uppercase, lowercase, number, and special character.';

    public function validatedBy(): string
    {
        return StrongPasswordValidator::class;
    }
}
