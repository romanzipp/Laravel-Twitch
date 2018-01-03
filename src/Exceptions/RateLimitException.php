<?php

namespace romanzipp\Twitch\Exceptions;

use Exception;

class RateLimitException extends Exception
{
    public function __construct()
    {
        $this->message = 'Rate Limit exceeded';
    }
}
