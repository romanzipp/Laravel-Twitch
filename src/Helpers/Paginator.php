<?php

namespace romanzipp\Twitch\Helpers;

class Paginator
{
    private $after;
    private $before;
    private $first;

    /**
     * Paginator constructor
     * @param mixed $after  Cursor for forward pagination: tells the server where to start fetching the next set of results, in a multi-page response
     * @param mixed $before Cursor for backward pagination: tells the server where to start fetching the next set of results, in a multi-page response
     * @param mixed $first  Number of values to be returned when getting results
     */
    public function __construct($after, $before, $first)
    {
        $this->after = $after;
        $this->before = $before;
        $this->first = $first;
    }

    /**
     * Static Paginator constructor
     * @param string $after  Cursor for forward pagination: tells the server where to start fetching the next set of results, in a multi-page response
     * @param string $before Cursor for backward pagination: tells the server where to start fetching the next set of results, in a multi-page response
     * @param string $first  Number of values to be returned when getting results
     */
    public static function from(string $after, string $before = null, string $first = null)
    {
        return new self($after, $before, $first);
    }
}
