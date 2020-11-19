<?php

namespace romanzipp\Twitch\Concerns\Operations;

use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait AbstractOperationsTrait
{
    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null): Result;

    abstract public function post(string $path = '', array $parameters = [], Paginator $paginator = null, array $body = null): Result;

    abstract public function put(string $path = '', array $parameters = [], Paginator $paginator = null, array $body = null): Result;

    abstract public function patch(string $path = '', array $parameters = [], Paginator $paginator = null, array $body = null): Result;

    abstract public function delete(string $path = '', array $parameters = [], Paginator $paginator = null, array $body = null): Result;

    abstract public function getClientId(): ?string;

    abstract public function getClientSecret(): ?string;

    abstract public function getRedirectUri(): ?string;
}
