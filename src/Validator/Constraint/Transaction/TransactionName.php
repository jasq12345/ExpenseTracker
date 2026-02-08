<?php

namespace App\Validator\Constraint\Transaction;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class TransactionName extends Constraint
{
    public string $message = 'Invalid data.';

    public function validatedBy(): string
    {
        return TransactionNameValidator::class;
    }
}
