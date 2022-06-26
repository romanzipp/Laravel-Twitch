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
     * @deprecated since 2021-12 https://discuss.dev.twitch.tv/t/deprecation-of-twitch-api-event-endpoints-that-supported-websub-based-webhooks/35137
     * @see https://dev.twitch.tv/docs/api/reference/#get-webhook-subscriptions
     *
     * @param array<string, mixed> $parameters
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
     * @deprecated since 2021-12 https://discuss.dev.twitch.tv/t/deprecation-of-twitch-api-event-endpoints-that-supported-websub-based-webhooks/35137
     * @see https://dev.twitch.tv/docs/api/webhooks-reference
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
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
     * @deprecated since 2021-12 https://discuss.dev.twitch.tv/t/deprecation-of-twitch-api-event-endpoints-that-supported-websub-based-webhooks/35137
     * @see https://dev.twitch.tv/docs/api/webhooks-reference
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
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
     * @deprecated since 2021-12 https://discuss.dev.twitch.tv/t/deprecation-of-twitch-api-event-endpoints-that-supported-websub-based-webhooks/35137
     * @see https://dev.twitch.tv/docs/api/webhooks-reference
     *
     * @param string $path
     * @param array<string, mixed> $parameters
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
