<?php

namespace romanzipp\Twitch\Concerns\Validation;

use InvalidArgumentException;

trait ValidationTrait
{
    protected function validateRequired(array $parameters, string $key): void
    {
        if (isset($parameters[$key])) {
            return;
        }

        throw new InvalidArgumentException("Required parameter `{$key}` is missing");
    }

    protected function validateAnyRequired(array $parameters, array $keys): void
    {
        $missingKeys = array_diff($keys, array_keys($parameters));

        if (empty($missingKeys)) {
            return;
        }

        throw new InvalidArgumentException(
            sprintf('Required parameters `%s` is missing', implode(', ', $missingKeys))
        );
    }

    protected function validateAllRequired(array $parameters, array $keys): void
    {
        foreach ($keys as $key) {

            if (isset($parameters[$key])) {
                continue;
            }

            throw new InvalidArgumentException("Required parameter `{$key}` is missing");
        }
    }
}
