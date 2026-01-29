<?php

namespace App\Validator;

interface AppValidatorInterface
{
    public function validateData($data): array;
}
