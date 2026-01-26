<?php

namespace App\Exception\Validation;

class MissingRequiredFieldException extends \InvalidArgumentException
{
    public function __construct(string $field)
    {
        parent::__construct("Missing field: $field");
    }
}
