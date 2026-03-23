<?php

namespace App\Validator\Constraint\Report;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ReportYearValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ReportYear) {
            throw new UnexpectedTypeException($constraint, ReportYear::class);
        }

        if (!is_int($value) || $value < 1) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}

