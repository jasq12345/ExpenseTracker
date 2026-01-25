<?php

namespace App\Auth\Exception;

class RefreshTokenExpiredException extends \Exception
{
    public function __construct(){
        parent::__construct('Refresh token expired');
    }
}
