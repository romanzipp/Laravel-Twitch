<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Result;

trait SearchTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Returns a list of games or categories that match the query via name either entirely or partially.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#search-categories
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function searchCategories(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['query']);

        return $this->get('search/categories', $parameters);
    }

    /**
     * Returns a list of channels (users who have streamed within the past 6 months) that match the query via channel name or description
     * either entirely or partially. Results include both live and offline channels. Online channels will have additional metadata
     * (e.g. started_at, tag_ids). See sample response for distinction.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#search-channels
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function searchChannels(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['query']);

        return $this->get('search/channels', $parameters);
    }
}
