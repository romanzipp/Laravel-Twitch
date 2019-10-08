<?php

namespace romanzipp\Twitch\Traits;

use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait SubscriptionsTrait
{
    /**
     * Gets subscriptions by user ID (one or more).
     * Required OAuth Scope: channel:read:subscriptions
     *
     * Parameters:
     * string broadcaster_id  ID of the broadcaster. Must match the User ID in the Bearer token.
     * string user_id         Unique identifier of account to get subscription status of. Accepts up to 100 values.
     *
     * @see  https://dev.twitch.tv/docs/api/reference/#get-user-subscriptions
     *
     * @param array $parameters Array of parameters
     * @param Paginator|null $paginator Paginator object
     * @return Result         Result object
     */
    public function getSubscriptions(array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->get('subscriptions', $parameters, $paginator);
    }

    /**
     * Get all of a user's subscriptions.
     * Required OAuth Scope: channel:read:subscriptions
     *
     * @see  https://dev.twitch.tv/docs/api/reference/#get-broadcaster-subscriptions
     *
     * @param int $user User ID
     * @param Paginator|null $paginator Paginator object
     * @return Result         Result object
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
     * @param Paginator|null $paginator Paginator object
     * @return Result         Result object
     */
    public function getBroadcasterSubscriptions(int $user, Paginator $paginator = null): Result
    {
        return $this->getSubscriptions(['broadcaster_id' => $user], $paginator);
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);
}
