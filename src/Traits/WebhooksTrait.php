<?php

namespace romanzipp\Twitch\Traits;

use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait WebhooksTrait
{
    public function subscribeWebhook(string $callback, string $topic, int $lease = null, string $secret = null): Result
    {
        $attributes = [
            'hub.callback' => $callback,
            'hub.mode' => 'subscribe',
            'hub.topic' => $topic,
        ];

        if ($lease !== null) {
            $attributes['hub.lease_seconds'] = $lease;
        }

        if ($secret !== null) {
            $attributes['hub.secret'] = $secret;
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

    public function webhookStreamMonitor(int $user): string
    {
        return static::BASE_URI . 'streams?user_id=' . $user;
    }

    public function webhookTopicUserFollows(int $from): string
    {
        return static::BASE_URI . 'users/follows?first=1&from_id=' . $from;
    }

    public function webhookTopicUserGainsFollower(int $to): string
    {
        return static::BASE_URI . 'users/follows?first=1&to_id=' . $from;
    }

    public function webhookTopicUserFollowsUser(int $from, int $to): string
    {
        return static::BASE_URI . 'users/follows?first=1&from_id=' . $from . '&to_id' . $from;
    }

    abstract public function post(string $path = '', array $parameters = [], Paginator $paginator = null);
}
