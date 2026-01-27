<?php

namespace App\Exception\Auth;

use InvalidArgumentException;

class AssociationNullException extends InvalidArgumentException
{
    public function __construct(string $field)
    {
        parent::__construct("Association $field is non-nullable but null was provided.");
    }
}
