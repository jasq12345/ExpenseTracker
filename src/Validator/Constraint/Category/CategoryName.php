<?php

namespace App\Validator\Constraint\Category;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class CategoryName extends Constraint
{
    public string $message = 'Invalid data.';

    public function validatedBy(): string
    {
        return CategoryNameValidator::class;
    }
}
