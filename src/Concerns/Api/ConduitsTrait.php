<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait ConduitsTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Gets the conduits for a client ID.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-conduits
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getConduits(array $parameters = []): Result
    {
        return $this->get('eventsub/conduits', $parameters);
    }

    /**
     * Creates a new conduit.
     *
     * @see https://dev.twitch.tv/docs/api/reference#create-conduits
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function createConduits(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, ['shard_count']);

        return $this->post('eventsub/conduits', $parameters, null, $body);
    }

    /**
     * Updates a conduit’s shard count. To delete shards, update the count to a lower number, and the shards above the count will be deleted. For example, if the existing shard count is 100, by resetting shard count to 50, shards 50-99 are disabled.
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-conduits
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function updateConduits(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, ['id', 'shard_count']);

        return $this->patch('eventsub/conduits', $parameters, null, $body);
    }

    /**
     * Deletes a specified conduit. Note that it may take some time for Eventsub subscriptions on a deleted conduit to show as disabled when calling Get Eventsub Subscriptions.
     *
     * @see https://dev.twitch.tv/docs/api/reference#delete-conduit
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function deleteConduit(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['id']);

        return $this->delete('eventsub/conduits', $parameters);
    }

    /**
     * Gets a lists of all shards for a conduit.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-conduit-shards
     *
     * @param array<string, mixed> $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     */
    public function getConduitShards(array $parameters = [], ?Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['conduit_id']);

        return $this->get('eventsub/conduits/shards', $parameters, $paginator);
    }

    /**
     * Updates shard(s) for a conduit. You can update up to 100 shards in a single request.
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-conduit-shards
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function updateConduitShards(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, ['conduit_id', 'shards']);

        return $this->patch('eventsub/conduits/shards', $parameters, null, $body);
    }
}
