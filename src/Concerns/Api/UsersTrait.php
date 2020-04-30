<?php

namespace romanzipp\Twitch\Concerns\Api;

use InvalidArgumentException;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait UsersTrait
{
    /**
     * Get currently authenticated user with Bearer Token
     *
     * @return Result Result object
     * @deprecated
     */
    public function getAuthedUser(): Result
    {
        return $this->get('users', [], null);
    }

    /**
     * Gets information about one or more specified Twitch users
     *
     * Parameters:
     * string   id     User ID. Multiple user IDs can be specified. Limit: 100.
     * string   login  User login name. Multiple login names can be specified. Limit: 100.
     *
     * @see    https://dev.twitch.tv/docs/api/reference#get-users
     *
     * @param array $parameters Array of parameters
     * @return Result Result object
     */
    public function getUsers(array $parameters): Result
    {
        if ( ! array_key_exists('login', $parameters) && ! array_key_exists('id', $parameters)) {
            throw new InvalidArgumentException('Parameter required missing: name or id');
        }

        return $this->get('users', $parameters);
    }

    /**
     * Gets information about one specified Twitch user by ID
     *
     * @see    https://dev.twitch.tv/docs/api/reference#get-users
     *
     * @param int $id User ID
     * @param array $parameters Additional parameters
     * @return Result Result object
     */
    public function getUserById(int $id, array $parameters = []): Result
    {
        $parameters['id'] = $id;

        return $this->getUsers($parameters);
    }

    /**
     * Gets information about one specified Twitch user by name
     *
     * @see    https://dev.twitch.tv/docs/api/reference#get-users
     *
     * @param string $name User name
     * @param array $parameters Additional parameters
     * @return Result Result object
     */
    public function getUserByName(string $name, array $parameters = []): Result
    {
        $parameters['login'] = $name;

        return $this->getUsers($parameters);
    }

    /**
     * Gets information about one or more specified Twitch users by IDs
     *
     * @see    https://dev.twitch.tv/docs/api/reference#get-users
     *
     * @param array $ids
     * @param array $parameters Additional parameters
     * @return Result Result object
     */
    public function getUsersByIds(array $ids, array $parameters = []): Result
    {
        $parameters['id'] = $ids;

        return $this->getUsers($parameters);
    }

    /**
     * Gets information about one or more specified Twitch users by names
     *
     * @see    https://dev.twitch.tv/docs/api/reference#get-users
     *
     * @param array $names
     * @param array $parameters Additional parameters
     * @return Result Result object
     */
    public function getUsersByNames(array $names, array $parameters = []): Result
    {
        $parameters['login'] = $names;

        return $this->getUsers($parameters);
    }

    /**
     * Updates the description of a user specified by a Bearer token
     *
     * @see    https://dev.twitch.tv/docs/api/reference#update-user
     *
     * @param string $description New description
     * @return Result Result object
     */
    public function updateUser(string $description): Result
    {
        return $this->put('users', [
            'description' => $description,
        ]);
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function post(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function put(string $path = '', array $parameters = [], Paginator $paginator = null);
}
