<?php

namespace romanzipp\Twitch\Exceptions;

use Throwable;

class RequestRequiresAuthenticationException extends Throwable
{
    public function __construct($message = 'Request requires authentication', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
