<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Result;

trait WebhooksTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Gets the Webhook subscriptions of a user identified by a Bearer token, in order of expiration.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-webhook-subscriptions
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getWebhookSubscriptions(array $parameters = []): Result
    {
        return $this->get('webhooks/subscriptions', $parameters);
    }

    /**
     * Subscribe to a webhook.
     *
     * @see https://dev.twitch.tv/docs/api/webhooks-reference
     *
     * @param array $parameters
     * @param array $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function subscribeWebhook(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, [
            'hub.callback',
            'hub.mode',
            'hub.topic',
            'hub.lease_seconds',
        ]);

        return $this->post('webhooks/hub', $parameters, null, $body);
    }

    /**
     * Unsubscribe from a webhook.
     *
     * @see https://dev.twitch.tv/docs/api/webhooks-reference
     *
     * @param array $parameters
     * @param array $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function unsubscribeWebhook(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, [
            'hub.callback',
            'hub.mode',
            'hub.topic',
        ]);

        return $this->post('webhooks/hub', $parameters, null, $body);
    }

    /**
     * Build the webhook topic url.
     *
     * @see https://dev.twitch.tv/docs/api/webhooks-reference
     *
     * @param string $path
     * @param array $parameters
     *
     * @return string
     */
    public function buildWebhookTopic(string $path, array $parameters = []): string
    {
        $url = static::BASE_URI . $path;

        if (empty($parameters)) {
            return $url;
        }

        $url .= '?' . http_build_query($parameters);

        return $url;
    }
}
