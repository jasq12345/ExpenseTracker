<?php

namespace App\Validator\Constraint\Report;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ReportCategories extends Constraint
{
    public string $message = 'Invalid data.';

    public function validatedBy(): string
    {
        return ReportCategoriesValidator::class;
    }
}

