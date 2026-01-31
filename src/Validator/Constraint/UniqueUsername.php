<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class UniqueUsername extends Constraint
{
    public string $message = 'Invalid data.';
    public string $messageConflict = 'Conflicting data';

    public function validatedBy(): string
    {
        return UniqueUsernameValidator::class;
    }
}
