<?php

namespace romanzipp\Twitch\Exceptions;

use Exception;

class SignatureVerificationException extends Exception
{
    public function __construct($message = 'Signature verification failed', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
