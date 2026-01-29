<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class StrongPasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof StrongPassword) {
            throw new UnexpectedTypeException($constraint, StrongPassword::class);
        }

        if (!$value || trim($value) === '') {
            $this->context->buildViolation($constraint->messageNotBlank)
                ->addViolation();
            return;
        }

        if (mb_strlen($value) < 8) {
            $this->context->buildViolation($constraint->messageTooShort)
                ->addViolation();
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*]).+$/', $value)) {
            $this->context->buildViolation($constraint->messageRegex)
                ->addViolation();
        }

        $notCompromised = new NotCompromisedPassword(['message' => $constraint->messageNotCompromisedPassword]);
        $violations = $this->context->getValidator()->validate($value, $notCompromised);

        foreach ($violations as $violation) {
            $this->context->buildViolation($violation->getMessage())
                ->addViolation();
        }
    }
}
