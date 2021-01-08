<?php

namespace romanzipp\Twitch\Objects;

use Illuminate\Contracts\Support\Arrayable;

class EventSubNotification implements Arrayable
{
    /**
     * Subscription information.
     *
     * @var array
     */
    public $subscription;

    /**
     *  Event data payload.
     *
     * @var array
     */
    public $event;

    public function __construct(array $data)
    {
        $this->subscription = $data['subscription'];
        $this->event = $data['event'];
    }

    public function toArray(): array
    {
        return [
            'subscription' => $this->subscription,
            'event' => $this->event,
        ];
    }

    public static function fromPayload(array $payload): self
    {
        return new self($payload);
    }
}
