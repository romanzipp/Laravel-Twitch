<?php

namespace romanzipp\Twitch\Traits;

use romanzipp\Twitch\Result;

trait WebhooksTrait
{
    public function subscribeWebhook(string $callback, string $topic, int $lease = null, string $secret = null): Result
    {
        $attributes = [
            'hub' => [
                'callback' => $callback,
                'mode' => 'subscribe',
                'topic' => $topic,
            ],
        ];

        if ($lease !== null) {
            $attributes['hub']['lease_seconds'] = $lease;
        }

        if ($secret !== null) {
            $attributes['hub']['secret'] = $secret;
        }

        return $this->post('webhooks/hub', $attributes);
    }

    public function unsubscribeWebhook(string $callback, string $topic): Result
    {
        $attributes = [
            'hub' => [
                'callback' => $callback,
                'mode' => 'unsubscribe',
                'topic' => $topic,
            ],
        ];

        return $this->post('webhooks/hub', $attributes);
    }

    abstract public function post(string $path = '', array $parameters = [], Paginator $paginator = null);
}
