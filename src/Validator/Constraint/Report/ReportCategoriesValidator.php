<?php

namespace App\Validator\Constraint\Report;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ReportCategoriesValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ReportCategories) {
            throw new UnexpectedTypeException($constraint, ReportCategories::class);
        }

        if ($value === null) {
            return;
        }

        if (!is_array($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return;
        }

        $normalized = [];

        foreach ($value as $categoryId) {
            if (!is_int($categoryId) || $categoryId < 1) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
                return;
            }

            $normalized[] = $categoryId;
        }

        if (count($normalized) !== count(array_unique($normalized))) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}

