<?php

namespace romanzipp\Twitch\Exceptions;

use Exception;

class RateLimitException extends Exception
{
    public function __construct($message = 'Rate Limit exceeded', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
