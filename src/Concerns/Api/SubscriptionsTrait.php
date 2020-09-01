<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Helpers\Paginator;
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
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator Paginator instance
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getSubscriptions(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('subscriptions', $parameters, $paginator);
    }
}
