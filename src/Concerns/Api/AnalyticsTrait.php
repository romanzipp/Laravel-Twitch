<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Result;

trait AnalyticsTrait
{
    use AbstractOperationsTrait;

    /**
     * Gets a URL that extension developers can use to download analytics reports (CSV files) for their extensions.
     * The URL is valid for 5 minutes.
     *
     * If you specify a future date, the response will be “Report Not Found For Date Range.” If you leave both started_at and
     * ended_at blank, the API returns the most recent date of data.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-extension-analytics
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getExtensionAnalytics(array $parameters = []): Result
    {
        return $this->get('analytics/extensions', $parameters);
    }

    /**
     * Gets a URL that game developers can use to download analytics reports (CSV files) for their games. The URL is valid for 5 minutes.
     *
     * The response has a JSON payload with a data field containing an array of games information elements and can contain a pagination
     * field containing information required to query for more streams.
     *
     * If you specify a future date, the response will be “Report Not Found For Date Range.” If you leave both started_at and ended_at blank,
     * the API returns the most recent date of data.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-game-analytics
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getGameAnalytics(array $parameters = []): Result
    {
        return $this->get('analytics/games', $parameters);
    }
}
