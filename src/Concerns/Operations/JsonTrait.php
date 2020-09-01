<?php

namespace romanzipp\Twitch\Concerns\Operations;

use romanzipp\Twitch\Result;

trait JsonTrait
{
    /**
     * @param string $method
     * @param string $path
     * @param array $parameters
     * @param array|null $body
     * @return \romanzipp\Twitch\Result
     */
    abstract public function json(string $method, string $path = '', array $parameters = [], array $body = null): Result;
}
