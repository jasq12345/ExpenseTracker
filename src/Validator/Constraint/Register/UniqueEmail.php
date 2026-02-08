<?php

namespace App\Validator\Constraint\Register;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class UniqueEmail extends Constraint
{
    public string $message = 'Invalid data';
    public string $messageConflict = 'Conflict';

    public function validatedBy(): string
    {
        return UniqueEmailValidator::class;
    }
}
