<?php

namespace romanzipp\Twitch\Traits;

use BadMethodCallException;
use romanzipp\Twitch\Result;

trait UsersTrait
{
    /**
     * Gets information about one or more specified Twitch users
     * @param  array  $parameters Array of parameters
     * @return Result             Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-users
     *
     * Parameters:
     * string   id     User ID. Multiple user IDs can be specified. Limit: 100.
     * string   login  User login name. Multiple login names can be specified. Limit: 100.
     */
    public function getUsers(array $parameters): Result
    {
        if (!array_key_exists('login', $parameters) && !array_key_exists('id', $parameters)) {
            throw new BadMethodCallException('Parameter required missing: name or id');
        }

        return $this->get('users', $parameters);
    }

    /**
     * Gets information about one specified Twitch user by ID
     * @param  int    $id         User ID
     * @param  array  $parameters Additional parameters
     * @return Result             Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-users
     */
    public function getUserById(int $id, array $parameters = []): Result
    {
        $parameters['id'] = $id;

        return $this->getUsers($parameters);
    }

    /**
     * Gets information about one specified Twitch user by name
     * @param  string $name       User name
     * @param  array  $parameters Additional parameters
     * @return Result             Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-users
     */
    public function getUserByName(string $name, array $parameters = []): Result
    {
        $parameters['login'] = $name;

        return $this->getUsers($parameters);
    }

    /**
     * Gets information about one or more specified Twitch users by IDs
     * @param  int    $id         User IDs
     * @param  array  $parameters Additional parameters
     * @return Result             Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-users
     */
    public function getUsersByIds(array $ids, array $parameters = []): Result
    {
        $parameters['id'] = $ids;

        return $this->getUsers($parameters);
    }

    /**
     * Gets information about one or more specified Twitch users by names
     * @param  int    $id         User names
     * @param  array  $parameters Additional parameters
     * @return Result             Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-users
     */
    public function getUsersByNames(array $names, array $parameters = []): Result
    {
        $parameters['login'] = $names;

        return $this->getUsers($parameters);
    }

    /**
     * Updates the description of a user specified by a Bearer token
     * @param  string $description New description
     * @return Result              Result object
     * @see    https://dev.twitch.tv/docs/api/reference#update-user
     */
    public function updateUser(string $description): Result
    {
        $this->put('users', [
            'description' => $description,
        ]);
    }
}
