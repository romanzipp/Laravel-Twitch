<?php

namespace romanzipp\Twitch\Concerns\Operations;

trait AbstractValidationTrait
{
    abstract protected function validateRequired(array $parameters, array $keys): void;

    abstract protected function validateAnyRequired(array $parameters, array $keys): void;
}
