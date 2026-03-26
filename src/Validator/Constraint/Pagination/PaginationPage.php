<?php

namespace App\Validator\Constraint\Pagination;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class PaginationPage extends Constraint
{
    public string $message = 'Invalid data.';

    public function validatedBy(): string
    {
        return PaginationPageValidator::class;
    }
}
