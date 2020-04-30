<?php

namespace romanzipp\Twitch\Concerns\Api;

use InvalidArgumentException;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait FollowsTrait
{
    /**
     * Gets information on follow relationships between two Twitch users
     *
     * @see    https://dev.twitch.tv/docs/api/reference#get-users-follows
     *
     * @param int $from User ID. The request returns information about users who are being followed by the from_id user
     * @param int|null $to User ID. The request returns information about users who are following the to_id user
     * @param Paginator|null $paginator Paginator object
     * @return Result         Result object
     */
    public function getFollows(int $from = null, int $to = null, Paginator $paginator = null): Result
    {
        if ($from === null && $to === null) {
            throw new InvalidArgumentException('At minimum, from or to must be provided for a query to be valid');
        }

        $parameters = [];

        if ($from !== null) {
            $parameters['from_id'] = $from;
        }

        if ($to !== null) {
            $parameters['to_id'] = $to;
        }

        return $this->get('users/follows', $parameters, $paginator);
    }

    /**
     * Gets information on follows from one user
     *
     * @see    https://dev.twitch.tv/docs/api/reference#get-users-follows
     *
     * @param int $from User ID. The request returns information about users who are being followed by the from_id user
     * @param Paginator|null $paginator Paginator object
     * @return Result         Result object
     */
    public function getFollowsFrom(int $from, Paginator $paginator = null): Result
    {
        return $this->getFollows($from, null, $paginator);
    }

    /**
     * Gets information on follows to one user
     *
     * @see    https://dev.twitch.tv/docs/api/reference#get-users-follows
     *
     * @param int $to User ID. The request returns information about users who are following the to_id user
     * @param Paginator|null $paginator Paginator object
     * @return Result         Result object
     */
    public function getFollowsTo(int $to, Paginator $paginator = null): Result
    {
        return $this->getFollows(null, $to, $paginator);
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function post(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function put(string $path = '', array $parameters = [], Paginator $paginator = null);
}
