<?php

namespace romanzipp\Twitch\Traits;

use BadMethodCallException;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait FollowsTrait
{
    /**
     * Gets information on follow relationships between two Twitch users
     * @param  int            $from      User ID. The request returns information about users who are being followed by the from_id user
     * @param  int|null       $to        User ID. The request returns information about users who are following the to_id user
     * @param  Paginator|null $paginator Paginator object
     * @return Result                    Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-users-follows
     */
    public function getFollows(int $from = null, int $to = null, Paginator $paginator = null): Result
    {
        if (!$from && !$to) {
            throw new BadMethodCallException('At minimum, from or to must be provided for a query to be valid');
        }

        $parameters = [];

        if ($from) {
            $parameters['from_id'] = $from;
        }

        if ($to) {
            $parameters['to_id'] = $to;
        }

        return $this->get('users/follows', $parameters, null, $paginator);
    }

    /**
     * Gets information on follows from one user
     * @param  int            $from      User ID. The request returns information about users who are being followed by the from_id user
     * @param  Paginator|null $paginator Paginator object
     * @return Result                    Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-users-follows
     */
    public function getFollowsFrom(int $from, Paginator $paginator = null): Result
    {
        return $this->getFollows($from, null, $paginator);
    }

    /**
     * Gets information on follows to one user
     * @param  int            $to        User ID. The request returns information about users who are following the to_id user
     * @param  Paginator|null $paginator Paginator object
     * @return Result                    Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-users-follows
     */
    public function getFollowsTo(int $to, Paginator $paginator = null): Result
    {
        return $this->getFollows(null, $to, $paginator);
    }
}
