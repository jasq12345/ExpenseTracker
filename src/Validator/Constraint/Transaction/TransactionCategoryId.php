<?php

namespace App\Validator\Constraint\Transaction;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class TransactionCategoryId extends Constraint
{
    public string $message = 'Invalid data.';

    public function validatedBy(): string
    {
        return TransactionCategoryIdValidator::class;
    }
}
