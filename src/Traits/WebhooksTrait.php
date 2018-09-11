<?php

namespace romanzipp\Twitch\Traits;

use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait WebhooksTrait
{
    /**
     * Subscribe to Events
     *
     * Notice: Twitch sends you an subscription verify or subscription denied request.
     *
     * Subscription verify:
     *     If your subscription request passes review, Twitch sends you a
     *     request to confirm that you requested the subscription. To confirm,
     *     you must respond to the request with the challenge token provided in
     *     the query parameters and an HTTP success (2xx) response code.
     *
     * Subscription denied:
     *     In this case, the request payload explains why the subscription
     *     could not be created. For example, you may not be authorized to access
     *     the resource you requested or you may have exceeded the maximum number of subscriptions.
     *
     * @param string $callback URL where notifications will be delivered.
     * @param string $topic Topic URL for the topic to subscribe to. topic maps to a new Twitch API endpoint.
     * @param int|null $lease
     * @param string|null $secret
     * @return Result Result object
     * @see https://dev.twitch.tv/docs/api/webhooks-reference/
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
     * Unsubscribe from Events
     * @param string $callback URL where notifications will be delivered.
     * @param string $topic Topic URL for the topic to unsubscribe from. topic maps to a new Twitch API endpoint.
     * @return Result Result object
     * @see https://dev.twitch.tv/docs/api/webhooks-reference/
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
     * Get Webhook Subscriptions
     * Note: Bearer OAuth Token is required
     * @param array $parameters Array of parameters
     * @param string|null $token Twitch OAuth Token
     * @return Result Result object
     * @see https://dev.twitch.tv/docs/api/reference/#get-webhook-subscriptions
     */
    public function getWebhookSubscriptions(string $token = null, array $parameters = []): Result
    {
        if ($token !== null) {
            $this->withToken($token);
        }

        return $this->get('webhooks/subscriptions', $parameters);
    }

    /**
     * Topic: Stream Up/Down
     * @param int $user
     * @return string Topic URL
     */
    public function webhookTopicStreamMonitor(int $user): string
    {
        return static::BASE_URI . 'streams?user_id=' . $user;
    }

    /**
     * Topic: User Changed
     * @param int $user
     * @return string Topic URL
     */
    public function webhookTopicUserChanged(int $user): string
    {
        return static::BASE_URI . 'users?id=' . $user;
    }

    /**
     * Topic: User Follows
     * @param int $from
     * @return string Topic URL
     */
    public function webhookTopicUserFollows(int $from): string
    {
        return static::BASE_URI . 'users/follows?first=1&from_id=' . $from;
    }

    /**
     * Topic: User Grains Follower
     * @param int $to
     * @return string Topic URL
     */
    public function webhookTopicUserGainsFollower(int $to): string
    {
        return static::BASE_URI . 'users/follows?first=1&to_id=' . $to;
    }

    /**
     * Topic: User Follows User
     * @param int $from
     * @param int $to
     * @return string Topic URL
     */
    public function webhookTopicUserFollowsUser(int $from, int $to): string
    {
        return static::BASE_URI . 'users/follows?first=1&from_id=' . $from . '&to_id' . $to;
    }

    /**
     * Topic: Game Analytics
     * @param int $game Specifies the game whose report data is provided.
     * @return string Topic URL
     */
    public function webhookTopicGameAnalytics(int $game): string
    {
        return static::BASE_URI . 'analytics/games?game_id=' . $game;
    }

    /**
     * Topic: Extension Analytics
     * @param string $extension Specifies the extension whose report data is provided. This is the client ID value assigned to the extension when it is created.
     * @return string Topic URL
     */
    public function webhookTopicExtensionAnalytics(string $extension): string
    {
        return static::BASE_URI . 'analytics/extensions?extension_id=' . $extension;
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function post(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function withToken(string $token);
}