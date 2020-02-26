<?php

namespace romanzipp\Twitch\Exceptions;

use Throwable;

class RequestRequiresClientIdException extends Throwable
{
    public function __construct($message = 'Request requires Client-ID', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
