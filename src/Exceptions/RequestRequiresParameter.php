<?php

namespace romanzipp\Twitch\Exceptions;

use Throwable;

class RequestRequiresParameter extends Throwable
{
    public function __construct($message = 'Request requires parameters', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
