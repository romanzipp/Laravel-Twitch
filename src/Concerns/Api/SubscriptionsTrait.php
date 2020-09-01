<?php

namespace romanzipp\Twitch\Concerns\Api;

use InvalidArgumentException;
use romanzipp\Twitch\Concerns\Operations\GetTrait;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait SubscriptionsTrait
{
    use GetTrait;

    /**
     * Gets subscriptions by user ID (one or more).
     * Required OAuth Scope: channel:read:subscriptions
     *
     * Parameters:
     * string broadcaster_id  ID of the broadcaster. Must match the User ID in the Bearer token. (required)
     * string user_id         Unique identifier of account to get subscription status of. Accepts up to 100 values.
     *
     * @see  https://dev.twitch.tv/docs/api/reference/#get-user-subscriptions
     *
     * @param array $parameters Array of parameters
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator Paginator instance
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getSubscriptions(array $parameters = [], Paginator $paginator = null): Result
    {
        if ( ! array_key_exists('broadcaster_id', $parameters)) {
            throw new InvalidArgumentException('Parameter required missing: broadcaster_id');
        }

        return $this->get('subscriptions', $parameters, $paginator);
    }

    /**
     * Get all of a user's subscriptions.
     * Required OAuth Scope: channel:read:subscriptions
     *
     * !!! The option to retrieve all users subscriptions without a broadcaster id has been removed.
     *
     * @see  https://dev.twitch.tv/docs/api/reference/#get-broadcaster-subscriptions
     *
     * @param int $user User ID
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator Paginator instance
     * @return \romanzipp\Twitch\Result Result instance
     * @deprecated
     *
     */
    public function getUserSubscriptions(int $user, Paginator $paginator = null): Result
    {
        return $this->getSubscriptions(['user_id' => $user], $paginator);
    }

    /**
     * Get all of a broadcaster's subscriptions.
     * Required OAuth Scope: channel:read:subscriptions
     *
     * @see  https://dev.twitch.tv/docs/api/reference/#get-broadcaster-subscriptions
     *
     * @param int $user Broadcaster ID
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator Paginator instance
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getBroadcasterSubscriptions(int $user, Paginator $paginator = null): Result
    {
        return $this->getSubscriptions(['broadcaster_id' => $user], $paginator);
    }
}
