<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\GetTrait;
use romanzipp\Twitch\Concerns\Operations\PutTrait;
use romanzipp\Twitch\Result;

trait UsersTrait
{
    use GetTrait;
    use PutTrait;

    /**
     * Gets information about one or more specified Twitch users. Returns the currently authenticated
     * user if no parameters are specified and an OAuth Token has been specified.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-users
     *
     * @param array $parameters Array of parameters
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getUsers(array $parameters = []): Result
    {
        return $this->get('users', $parameters);
    }

    /**
     * Gets information about one specified Twitch user by ID
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-users
     *
     * @param int $id User ID
     * @param array $parameters Additional parameters
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getUserById(int $id, array $parameters = []): Result
    {
        $parameters['id'] = $id;

        return $this->getUsers($parameters);
    }

    /**
     * Gets information about one specified Twitch user by name
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-users
     *
     * @param string $name User name
     * @param array $parameters Additional parameters
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getUserByName(string $name, array $parameters = []): Result
    {
        $parameters['login'] = $name;

        return $this->getUsers($parameters);
    }

    /**
     * Gets information about one or more specified Twitch users by IDs
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-users
     *
     * @param array $ids
     * @param array $parameters Additional parameters
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getUsersByIds(array $ids, array $parameters = []): Result
    {
        $parameters['id'] = $ids;

        return $this->getUsers($parameters);
    }

    /**
     * Gets information about one or more specified Twitch users by names
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-users
     *
     * @param array $names
     * @param array $parameters Additional parameters
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getUsersByNames(array $names, array $parameters = []): Result
    {
        $parameters['login'] = $names;

        return $this->getUsers($parameters);
    }

    /**
     * Updates the description of a user specified by a Bearer token
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-user
     *
     * @param string $description New description
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function updateUser(string $description): Result
    {
        return $this->put('users', [
            'description' => $description,
        ]);
    }
}
