<?php

namespace romanzipp\Twitch\Objects;

use Illuminate\Contracts\Support\Arrayable;

class EventSubNotification implements Arrayable
{
    public $subscription;

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

    public static function fromPayload(array $payload)
    {
        return new self($payload);
    }
}
