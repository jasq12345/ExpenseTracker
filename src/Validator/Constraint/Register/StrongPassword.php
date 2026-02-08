<?php

namespace App\Validator\Constraint\Register;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class StrongPassword extends Constraint
{
    public string $message = 'Invalid data.';
    public function validatedBy(): string
    {
        return StrongPasswordValidator::class;
    }
}
