<?php

namespace romanzipp\Twitch\Exceptions;

use Throwable;

class RateLimitException extends Throwable
{
    public function __construct($message = 'Rate Limit exceeded', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
