<?php

namespace romanzipp\Twitch\Exceptions;

use Exception;

class RequestRequiresRedirectUriException extends Exception
{
    public function __construct($message = 'Request requires redirect uri', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
