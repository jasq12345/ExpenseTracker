<?php

namespace App\Exception\Auth;

class RefreshTokenNotFoundException extends \Exception
{
    public function __construct(){
        parent::__construct('Refresh token not found');
    }
}
