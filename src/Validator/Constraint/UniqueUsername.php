<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class UniqueUsername extends Constraint
{
 public string $messageNotBlank = 'Username is required.';
 public string $messageUnique = 'This username is already used.';
 public string $messageTooShort = 'Username must be at least 3 characters.';
 public string $messageTooLong = 'Username cannot exceed 50 characters.';

 public function validatedBy(): string
 {
     return UniqueUsernameValidator::class;
 }
}
