<?php

namespace App\Validator\Constraint\Category;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CategoryColorValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CategoryColor) {
            throw new UnexpectedTypeException($constraint, CategoryColor::class);
        }

        // nullable — skip if null
        if ($value === null) {
            return;
        }

        // Expect a hex color like #ff00aa (7 chars)
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
