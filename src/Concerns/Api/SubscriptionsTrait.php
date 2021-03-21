<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait SubscriptionsTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Get all of a broadcaster’s subscriptions.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-broadcaster-subscriptions
     *
     * @param array $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator Paginator instance
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getSubscriptions(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('subscriptions', $parameters, $paginator);
    }

    /**
     * Checks if a specific user (user_id) is subscribed to a specific channel (broadcaster_id).
     *
     * @see https://dev.twitch.tv/docs/api/reference#check-user-subscription
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getUserSubscription(array $parameters = []): Result
    {
        $this->validateAnyRequired($parameters, ['broadcaster_id', 'user_id']);

        return $this->get('subscriptions/user', $parameters);
    }
}
