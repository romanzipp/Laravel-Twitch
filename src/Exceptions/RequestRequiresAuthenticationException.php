<?php

namespace romanzipp\Twitch\Exceptions;

use Exception;

class RequestRequiresAuthenticationException extends Exception
{
    public function __construct()
    {
        $this->message = 'Request requires authentication';
    }
}
