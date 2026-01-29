<?php
/** @noinspection ALL */

namespace App\Validator\Constraint;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class UniqueEmail extends Constraint
{
    public string $messageNotBlank = 'Email is required.';
    public string $messageInvalid = 'Email must be a valid email address.';
    public string $messageUnique = 'This email is already used.';

    public function validatedBy(): string
    {
        return UniqueEmailValidator::class;
    }
}
