<?php

namespace romanzipp\Twitch\Concerns\Operations;

trait JsonTrait
{
    /**
     * @param string $method
     * @param string $path
     * @param array|null $body
     * @return \romanzipp\Twitch\Result
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresClientIdException
     */
    abstract public function json(string $method, string $path = '', array $body = null);
}
