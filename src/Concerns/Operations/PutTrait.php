<?php

namespace romanzipp\Twitch\Concerns\Operations;

use romanzipp\Twitch\Helpers\Paginator;

trait PutTrait
{
    /**
     * @param string $path
     * @param array $parameters
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator
     * @return \romanzipp\Twitch\Result
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresClientIdException
     */
    abstract public function put(string $path = '', array $parameters = [], Paginator $paginator = null);
}
