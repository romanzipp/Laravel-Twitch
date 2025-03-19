<?php

namespace romanzipp\Twitch\Objects;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
class EventSubNotification implements Arrayable
{
    /**
     * Subscription information.
     *
     * @var mixed
     */
    public $subscription;

    /**
     *  Event data payload.
     *
     * @var mixed
     */
    public $event;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->subscription = $data['subscription'];
        $this->event = $data['event'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'subscription' => $this->subscription,
            'event' => $this->event,
        ];
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromPayload(array $payload): self
    {
        return new self($payload);
    }
}
