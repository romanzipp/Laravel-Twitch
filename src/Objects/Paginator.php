<?php

namespace romanzipp\Twitch\Objects;

use romanzipp\Twitch\Result;
use stdClass;

class Paginator
{
    /**
     * Twitch response pagination cursor.
     *
     * @var stdClass|null
     */
    private $pagination;

    /**
     * Next desired action (first, after, before).
     *
     * @var string|null
     */
    public $action = null;

    /**
     * Constructor.
     *
     * @param stdClass|null $pagination Twitch response pagination cursor
     */
    public function __construct(stdClass $pagination = null)
    {
        $this->pagination = $pagination;
    }

    /**
     * Create Paginator from Result instance.
     *
     * @param \romanzipp\Twitch\Result $result Result instance
     *
     * @return self Paginator instance
     */
    public static function from(Result $result): self
    {
        return new self(
            $result->getPagination()
        );
    }

    /**
     * Return the current active cursor.
     *
     * @return string|null Twitch cursor
     */
    public function cursor(): ?string
    {
        return $this->pagination->cursor ?? null;
    }

    /**
     * Set the Paginator to fetch the first set of results.
     *
     * @return self
     */
    public function next(): self
    {
        $this->action = 'after';

        return $this;
    }

    /**
     * Set the Paginator to fetch the last set of results.
     *
     * @return self
     */
    public function back(): self
    {
        $this->action = 'before';

        return $this;
    }
}
