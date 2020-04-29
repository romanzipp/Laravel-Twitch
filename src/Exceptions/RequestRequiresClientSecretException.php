<?php

namespace romanzipp\Twitch\Exceptions;

use Exception;

class RequestRequiresClientSecretException extends Exception
{
    public function __construct($message = 'Request requires Client-Secret', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
