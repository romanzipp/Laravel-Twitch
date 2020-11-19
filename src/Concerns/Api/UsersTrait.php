<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait UsersTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Adds a specified user to the followers of a specified channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#create-user-follows
     *
     * @param array $parameters
     * @param array $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function createUserFollows(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($parameters, ['from_id', 'to_id']);

        return $this->post('users/follows', $parameters, null, $body);
    }

    /**
     * Deletes a specified user from the followers of a specified channel.
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function deleteUserFollows(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['from_id', 'to_id']);

        return $this->delete('users/follows', $parameters);
    }

    /**
     * Gets information about one or more specified Twitch users. Users are identified by optional user IDs
     * and/or login name. If neither a user ID nor a login name is specified, the user is looked up by Bearer token.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-users
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function getUsers(array $parameters = []): Result
    {
        return $this->get('users', $parameters);
    }

    /**
     * Gets information on follow relationships between two Twitch users. Information returned is sorted in order, most
     * recent follow first. This can return information like “who is qotrok following,” “who is following qotrok,” or
     * “is user X following user Y.”.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-users-follows
     *
     * @param array $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getUsersFollows(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateAnyRequired($parameters, ['from_id', 'to_id']);

        return $this->get('users/follows', $parameters, $paginator);
    }

    /**
     * Updates the description of a user specified by a Bearer token.
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-user
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function updateUser(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['description']);

        return $this->put('users', $parameters);
    }

    /**
     * Gets a list of all extensions (both active and inactive) for a specified user, identified by a Bearer token.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-user-extensions
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getUserExtensions(array $parameters = []): Result
    {
        return $this->get('users/extensions/list', $parameters);
    }

    /**
     * Gets information about active extensions installed by a specified user, identified by a user ID or Bearer token.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#get-user-active-extensions
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getUserActiveExtensions(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['user_id']);

        return $this->get('users/extensions', $parameters);
    }

    /**
     * Updates the activation state, extension ID, and/or version number of installed extensions for a specified user,
     * identified by a Bearer token. If you try to activate a given extension under multiple extension types, the last
     * write wins (and there is no guarantee of write order).
     *
     * @see https://dev.twitch.tv/docs/api/reference/#update-user-extensions
     *
     * @param array $parameters
     * @param array $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function updateUserExtension(array $parameters = [], array $body = []): Result
    {
        return $this->put('users/extensions', $parameters, null, $body);
    }
}
