<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait ChannelPoints
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Creates a Custom Reward on a channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference#create-custom-rewards
     *
     * @param array $parameters
     * @param array $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function createCustomRewards(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);
        $this->validateRequired($body, ['title', 'cost']);

        return $this->post('channel_points/custom_rewards', $parameters, null, $body);
    }

    /**
     * Deletes a Custom Reward on a channel.
     * Only rewards created programmatically by the same client_id can be deleted. Any UNFULFILLED Custom Reward Redemptions of the deleted Custom Reward will be
     * updated to the FULFILLED status.
     *
     * @see https://dev.twitch.tv/docs/api/reference#delete-custom-reward
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function deleteCustomReward(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'id']);

        return $this->delete('channel_points/custom_rewards', $parameters);
    }

    /**
     * Returns a list of Custom Reward objects for the Custom Rewards on a channel. Developers only have access to update and delete rewards that were created programmatically by the same/calling client_id.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-custom-reward
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getCustomReward(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('channel_points/custom_rewards', $parameters);
    }

    /**
     * Returns Custom Reward Redemption objects for a Custom Reward on a channel that was created by the same client_id.
     * Developers only have access to get and update redemptions for the rewards created programmatically by the same client_id.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-custom-reward-redemption
     *
     * @param array $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     *
     * @return \romanzipp\Twitch\Result
     */
    public function getCustomRewardRedemption(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'reward_id']);

        return $this->get('channel_points/custom_rewards/redemptions', $parameters, $paginator);
    }

    /**
     * Updates a Custom Reward created on a channel.
     * Only rewards created programmatically by the same client_id can be updated.
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-custom-reward
     *
     * @param array $parameters
     *
     * @return \romanzipp\Twitch\Result
     */
    public function updateCustomReward(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'id']);

        return $this->patch('channel_points/custom_rewards', $parameters);
    }

    /**
     * Updates the status of Custom Reward Redemption objects on a channel that are in the UNFULFILLED status.
     * Only redemptions for a reward created programmatically by the same client_id as attached to the access token can be updated.
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-redemption-status
     *
     * @param array $parameters
     * @param array $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function updateRedemptionStatus(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($parameters, ['id', 'broadcaster_id', 'reward_id']);
        $this->validateRequired($body, ['status']);

        return $this->patch('channel_points/custom_rewards/redemptions', $parameters, null, $body);
    }
}
