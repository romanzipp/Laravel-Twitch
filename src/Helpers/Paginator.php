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

    public static function from(Result $result)
    {
        return new self($result->pagination);
    }

    public function cursor()
    {
        return $this->pagination->cursor;
    }

    public function first()
    {
        $this->action = 'first';

        return $this;
    }

    public function next()
    {
        $this->action = 'after';

        return $this;
    }

    public function back()
    {
        $this->action = 'before';

        return $this;
    }
}
