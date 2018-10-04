<?php

namespace romanzipp\Twitch\Exceptions;

use Exception;

class RequestRequiresParameter extends Exception
{
    public function __construct($message = 'Request requires parameters', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
