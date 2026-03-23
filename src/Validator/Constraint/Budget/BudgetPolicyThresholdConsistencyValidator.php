<?php

namespace App\Validator\Constraint\Budget;

use App\Enum\BudgetPolicyEnum;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class BudgetPolicyThresholdConsistencyValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof BudgetPolicyThresholdConsistency) {
            throw new UnexpectedTypeException($constraint, BudgetPolicyThresholdConsistency::class);
        }

        if (!is_object($value) || !property_exists($value, 'policy') || !property_exists($value, 'warningThreshold')) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return;
        }

        $policy = $value->policy;
        $warningThreshold = $value->warningThreshold;

        if (!$policy instanceof BudgetPolicyEnum) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return;
        }

        if ($policy === BudgetPolicyEnum::UNLIMITED && $warningThreshold !== null) {
            $this->context->buildViolation($constraint->message)
                ->atPath('warningThreshold')
                ->addViolation();
            return;
        }

        if ($policy !== BudgetPolicyEnum::UNLIMITED && $warningThreshold === null) {
            $this->context->buildViolation($constraint->message)
                ->atPath('warningThreshold')
                ->addViolation();
        }
    }
}

