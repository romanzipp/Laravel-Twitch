<?php

namespace romanzipp\Twitch\Events;

use Carbon\CarbonInterface;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventSubHandled
{
    use Dispatchable;
    use SerializesModels;

    /**
     * The webhook payload.
     *
     * @var array
     */
    public $payload;

    /**
     * The ID for the event provided by Twitch
     *
     * @var string
     */
    public $id;

    /**
     * The number of retries to receive this event
     *
     * @var int
     */
    public $retries;

    /**
     * The timestamp of when the event was sent the first time
     *
     * @var CarbonInterface
     */
    public $timestamp;

    /**
     * Create a new event instance.
     *
     * @param array $payload
     * @param string $id
     * @param int $retries
     * @param CarbonInterface $timestamp
     */
    public function __construct(array $payload, string $id, int $retries, CarbonInterface $timestamp)
    {
        $this->payload = $payload;
        $this->id = $id;
        $this->retries = $retries;
        $this->timestamp = $timestamp;
    }
}
