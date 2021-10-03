<?php

namespace romanzipp\Twitch\Concerns\Validation;

use InvalidArgumentException;

trait ValidationTrait
{
    /**
     * @param array<string, mixed> $parameters
     * @param string[] $keys
     */
    protected function validateRequired(array $parameters, array $keys): void
    {
        foreach ($keys as $key) {
            if (isset($parameters[$key])) {
                continue;
            }

            throw new InvalidArgumentException("Required parameter `{$key}` is missing");
        }
    }

    /**
     * @param array<string, mixed> $parameters
     * @param string[] $keys
     */
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
