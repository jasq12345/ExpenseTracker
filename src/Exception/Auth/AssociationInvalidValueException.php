<?php

namespace App\Exception\Auth;

use InvalidArgumentException;

class AssociationInvalidValueException extends InvalidArgumentException
{
    public function __construct(string $field){
        parent::__construct("Invalid value for association: $field");
    }
}
