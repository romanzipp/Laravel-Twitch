<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\GetTrait;
use romanzipp\Twitch\Concerns\Operations\PostTrait;
use romanzipp\Twitch\Result;

trait WebhooksTrait
{
    use GetTrait;
    use PostTrait;

    /**
     * Subscribe to a webhook.
     *
     * @param string $callback
     * @param string $topic
     * @param int|null $lease
     * @param string|null $secret
     * @return \romanzipp\Twitch\Result
     */
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

    /**
     * Unsubscribe from a webhook.
     *
     * @param string $callback
     * @param string $topic
     * @return \romanzipp\Twitch\Result
     */
    public function unsubscribeWebhook(string $callback, string $topic): Result
    {
        $attributes = [
            'hub.callback' => $callback,
            'hub.mode' => 'unsubscribe',
            'hub.topic' => $topic,
        ];

        return $this->post('webhooks/hub', $attributes);
    }

    /**
     * Get webhook subscriptions.
     *
     * @param array $parameters
     * @return \romanzipp\Twitch\Result
     */
    public function getWebhookSubscriptions(array $parameters = []): Result
    {
        return $this->get('webhooks/subscriptions', $parameters);
    }

    /**
     * Build the webhook "streams" URL.
     *
     * @param int $user
     * @return string
     */
    public function webhookTopicStreamMonitor(int $user): string
    {
        return static::BASE_URI . 'streams?' . http_build_query([
                'user_id' => $user,
            ]);
    }

    /**
     * Build the webhook "from follows" URL.
     *
     * @param int $from
     * @return string
     */
    public function webhookTopicUserFollows(int $from): string
    {
        return static::BASE_URI . 'users/follows?' . http_build_query([
                'first' => 1,
                'from_id' => $from,
            ]);
    }

    /**
     * Build the webhook "to follows" URL.
     *
     * @param int $to
     * @return string
     */
    public function webhookTopicUserGainsFollower(int $to): string
    {
        return static::BASE_URI . 'users/follows?' . http_build_query([
                'first' => 1, 'to_id' => $to,
            ]);
    }

    /**
     * Build the webhook "from & to follows" URL.
     *
     * @param int $from
     * @param int $to
     * @return string
     */
    public function webhookTopicUserFollowsUser(int $from, int $to): string
    {
        return static::BASE_URI . 'users/follows?' . http_build_query([
                'first' => 1,
                'from_id' => $from,
                'to_id' => $to,
            ]);
    }
}
