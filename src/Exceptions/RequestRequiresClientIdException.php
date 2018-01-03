<?php

namespace romanzipp\Twitch\Exceptions;

use Exception;

class RequestRequiresClientIdException extends Exception
{
    public function __construct()
    {
        $this->message = 'Request requires Client-ID';
    }
}
