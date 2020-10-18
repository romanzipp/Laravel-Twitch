<?php

namespace romanzipp\Twitch\Concerns\Validation;

use InvalidArgumentException;

trait ValidationTrait
{
    protected function validateRequired(array $parameters, array $keys): void
    {
        foreach ($keys as $key) {
            if (isset($parameters[$key])) {
                continue;
            }

            throw new InvalidArgumentException("Required parameter `{$key}` is missing");
        }
    }

    protected function validateAnyRequired(array $parameters, array $keys): void
    {
        foreach ($keys as $key) {
            if ( ! isset($parameters[$key])) {
                continue;
            }

            return;
        }

        throw new InvalidArgumentException(sprintf('One or more fields `%s` must be specified', implode(', ', $keys)));
    }
}
