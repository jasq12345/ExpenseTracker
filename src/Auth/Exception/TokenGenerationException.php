<?php

namespace App\Auth\Exception;

use Exception;

class TokenGenerationException extends Exception
{
    public function __construct()
    {
        parent::__construct('Failed to generate secure token');
    }
}
