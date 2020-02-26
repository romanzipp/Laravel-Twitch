<?php

namespace romanzipp\Twitch\Exceptions;

use Throwable;

class RequestRequiresRedirectUriException extends Throwable
{
    public function __construct($message = 'Request requires redirect uri', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
