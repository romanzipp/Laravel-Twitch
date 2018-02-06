<?php

namespace romanzipp\Twitch\Exceptions;

use Exception;

class RequestRequiresAuthenticationException extends Exception
{
    public function __construct(string $message = null)
    {
        $this->message = $message ?? 'Request requires authentication';
    }
}
