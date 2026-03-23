<?php

namespace App\Validator\Constraint\Report;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ReportMonthValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ReportMonth) {
            throw new UnexpectedTypeException($constraint, ReportMonth::class);
        }

        if (!is_int($value) || $value < 1 || $value > 12) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}

