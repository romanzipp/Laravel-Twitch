<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Result;

trait AdsTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Starts a commercial on a specified channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference#start-commercial
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result Result instance
     */
    public function startCommercial(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'length']);

        return $this->post('channels/commercial', $parameters);
    }

    /**
     * This endpoint returns ad schedule related information, including snooze, when the last ad was run, when the next ad is scheduled, and if the channel is currently in pre-roll free time. Note that a new ad cannot be run until 8 minutes after running a previous ad.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-ad-schedule
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getAdSchedule(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('channels/ads', $parameters);
    }

    /**
     * If available, pushes back the timestamp of the upcoming automatic mid-roll ad by 5 minutes. This endpoint duplicates the snooze functionality in the creator dashboard’s Ads Manager.
     *
     * @see https://dev.twitch.tv/docs/api/reference#snooze-next-ad
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function snoozeNextAd(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->post('channels/ads/schedule/snooze', $parameters);
    }
}
