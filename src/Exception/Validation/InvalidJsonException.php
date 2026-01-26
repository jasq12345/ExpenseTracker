<?php

namespace App\Exception\Validation;

class InvalidJsonException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("Invalid JSON");
    }
}
