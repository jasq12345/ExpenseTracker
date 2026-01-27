<?php

namespace App\Exception\Auth;

class EnumInvalidValueException extends \InvalidArgumentException
{
    public function __construct(string $field){
        parent::__construct("Invalid value for enum: $field");
    }
}
