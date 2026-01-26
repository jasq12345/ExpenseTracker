<?php

namespace App\Exception\Auth;

class RefreshTokenExpiredException extends \Exception
{
    public function __construct(){
        parent::__construct('Refresh token expired');
    }
}
