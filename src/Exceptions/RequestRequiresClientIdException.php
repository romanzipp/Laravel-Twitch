<?php

namespace romanzipp\Twitch\Exceptions;

use Exception;

class RequestRequiresClientIdException extends Exception
{
    public function __construct($message = 'Request requires Client-ID', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
