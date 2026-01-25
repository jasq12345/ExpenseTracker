<?php

namespace App\Auth\Exception;

class RefreshTokenNotFoundException extends \Exception
{
    public function __construct(){
        parent::__construct('Refresh token not found');
    }
}
