<?php

namespace romanzipp\Twitch\Concerns\Operations;

use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait PostTrait
{
    /**
     * @param string $path
     * @param array $parameters
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator
     * @return \romanzipp\Twitch\Result
     */
    abstract public function post(string $path = '', array $parameters = [], Paginator $paginator = null): Result;
}
