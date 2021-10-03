<?php

namespace romanzipp\Twitch\Exceptions;

use Exception;

class RequestRequiresAuthenticationException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = 'Request requires authentication', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
