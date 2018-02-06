<?php

namespace romanzipp\Twitch\Traits\Legacy;

use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait RootTrait
{
    public function legacyRoot()
    {
        return $this->withLegacy()->get('/kraken');
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function post(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function put(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function withLegacy();
}
