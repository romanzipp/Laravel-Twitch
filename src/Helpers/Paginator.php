<?php

namespace romanzipp\Twitch\Helpers;

use romanzipp\Twitch\Result;
use stdClass;

class Paginator
{
    private $pagination;

    public $action = null;

    public function __construct(stdClass $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * Create Paginator from Result object
     * @param  Result $result Result object
     * @return self           Paginator object
     */
    public static function from(Result $result)
    {
        return new self($result->pagination);
    }

    /**
     * Return the current active cursor
     * @return string Twitch cursor
     */
    public function cursor(): string
    {
        return $this->pagination->cursor;
    }

    /**
     * Set the Paginator to fetch the next set of results
     * @return self
     */
    public function first()
    {
        $this->action = 'first';

        return $this;
    }

    /**
     * Set the Paginator to fetch the first set of results
     * @return self
     */
    public function next()
    {
        $this->action = 'after';

        return $this;
    }

    /**
     * Set the Paginator to fetch the last set of results
     * @return self
     */
    public function back()
    {
        $this->action = 'before';

        return $this;
    }
}
