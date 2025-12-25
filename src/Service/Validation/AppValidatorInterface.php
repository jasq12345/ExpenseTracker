<?php

namespace App\Service\Validation;

interface AppValidatorInterface
{
    public function validateData($data): array;
}
