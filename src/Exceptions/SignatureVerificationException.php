<?php

namespace romanzipp\Twitch\Exceptions;

class SignatureVerificationException extends \Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = 'Signature verification failed', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
