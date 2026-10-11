<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait CharityTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Gets information about the charity campaign that a broadcaster is running, such as their fundraising goal and the amount that’s been donated so far.
     *
     * To receive events as donations occur, use the channel.charity_campaign.donate subscription type.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-charity-campaign
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getCharityCampaign(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('charity/campaigns');
    }

    /**
     * Gets the list of donations that users have made to the broadcaster’s active charity campaign.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-charity-campaign-donations
     *
     * @param array<string, mixed> $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     */
    public function getCharityCampaignDonations(array $parameters = [], ?Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('charity/donations', $parameters, $paginator);
    }
}
