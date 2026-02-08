<?php

namespace App\Validator\Constraint\Register;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class UniqueUsername extends Constraint
{
    public string $message = 'Invalid data.';
    public string $messageConflict = 'Conflict';

    public function validatedBy(): string
    {
        return UniqueUsernameValidator::class;
    }
}
