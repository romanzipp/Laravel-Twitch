<?php

namespace romanzipp\Twitch\Exceptions;

use Exception;

class RequestRequiresParameter extends Exception
{
    public function __construct($message = 'Request requires parameters')
    {
        $this->message = $message;
    }
}
