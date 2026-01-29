<?php

namespace App\Validator\Constraint;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUsernameValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ){}

    public function validate($value, Constraint $constraint): void
    {
        if(!$constraint instanceof UniqueUsername){
            return;
        }

        $value = trim((string)$value);

        if ($value === '') {
            $this->context->buildViolation($constraint->messageNotBlank)
                ->addViolation();
            return;
        }

        if (mb_strlen($value) < 3) {
            $this->context->buildViolation($constraint->messageTooShort)
                ->addViolation();
        }

        if (mb_strlen($value) > 50) {
            $this->context->buildViolation($constraint->messageTooLong)
                ->addViolation();
        }

        $existing = $this->em->getRepository(User::class)
            ->findOneBy(['username' => $value]);

        if ($existing) {
            $this->context->buildViolation($constraint->messageUnique)
                ->addViolation();
        }
    }
}
