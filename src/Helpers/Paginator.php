<?php

namespace romanzipp\Twitch\Helpers;

class Paginator
{
    private $after;
    private $before;
    private $first;

    public function __construct($after, $before, $first)
    {
        $this->after = $after;
        $this->before = $before;
        $this->first = $first;
    }

    public static function from(string $after, string $before = null, string $first = null){
        return new self($after, $before, $first);
    }
}
