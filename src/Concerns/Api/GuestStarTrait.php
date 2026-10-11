<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Result;

trait GuestStarTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Gets the channel settings for configuration of the Guest Star feature for a particular host.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-channel-guest-star-settings
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getChannelGuestStarSettings(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id']);

        return $this->get('guest_star/channel_settings', $parameters);
    }

    /**
     * Mutates the channel settings for configuration of the Guest Star feature for a particular host.
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-channel-guest-star-settings
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function updateChannelGuestStarSettings(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->put('guest_star/channel_settings', $parameters, null, $body);
    }

    /**
     * Gets information about an ongoing Guest Star session for a particular channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-guest-star-session
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getGuestStarSession(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id']);

        return $this->get('guest_star/session', $parameters);
    }

    /**
     * Programmatically creates a Guest Star session on behalf of the broadcaster. Requires the broadcaster to be present in the call interface, or the call will be ended automatically.
     *
     * @see https://dev.twitch.tv/docs/api/reference#create-guest-star-session
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function createGuestStarSession(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->post('guest_star/session', $parameters);
    }

    /**
     * Programmatically ends a Guest Star session on behalf of the broadcaster. Performs the same action as if the host clicked the “End Call” button in the Guest Star UI.
     *
     * @see https://dev.twitch.tv/docs/api/reference#end-guest-star-session
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function endGuestStarSession(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'session_id']);

        return $this->delete('guest_star/session', $parameters);
    }

    /**
     * Provides the caller with a list of pending invites to a Guest Star session, including the invitee’s ready status while joining the waiting room.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-guest-star-invites
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getGuestStarInvites(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id', 'session_id']);

        return $this->get('guest_star/invites', $parameters);
    }

    /**
     * Sends an invite to a specified guest on behalf of the broadcaster for a Guest Star session in progress.
     *
     * @see https://dev.twitch.tv/docs/api/reference#send-guest-star-invite
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function sendGuestStarInvite(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id', 'session_id', 'guest_id']);

        return $this->post('guest_star/invites', $parameters);
    }

    /**
     * Revokes a previously sent invite for a Guest Star session.
     *
     * @see https://dev.twitch.tv/docs/api/reference#delete-guest-star-invite
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function deleteGuestStarInvite(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id', 'session_id', 'guest_id']);

        return $this->delete('guest_star/invites', $parameters);
    }

    /**
     * Allows a previously invited user to be assigned a slot within the active Guest Star session, once that guest has indicated they are ready to join.
     *
     * @see https://dev.twitch.tv/docs/api/reference#assign-guest-star-slot
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function assignGuestStarSlot(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id', 'session_id', 'guest_id', 'slot_id']);

        return $this->post('guest_star/slot', $parameters);
    }

    /**
     * Allows a user to update the assigned slot for a particular user within the active Guest Star session.
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-guest-star-slot
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function updateGuestStarSlot(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id', 'session_id', 'source_slot_id']);

        return $this->patch('guest_star/slot', $parameters);
    }

    /**
     * Allows a caller to remove a slot assignment from a user participating in an active Guest Star session. This revokes their access to the session immediately and disables their access to publish or subscribe to media within the session.
     *
     * @see https://dev.twitch.tv/docs/api/reference#delete-guest-star-slot
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function deleteGuestStarSlot(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id', 'session_id', 'guest_id', 'slot_id']);

        return $this->delete('guest_star/slot', $parameters);
    }

    /**
     * Allows a user to update slot settings for a particular guest within a Guest Star session, such as allowing the user to share audio or video within the call as a host. These settings will be broadcasted to all subscribers which control their view of the guest in that slot. One or more of the optional parameters to this API can be specified at any time.
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-guest-star-slot-settings
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function updateGuestStarSlotSettings(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'moderator_id', 'session_id', 'slot_id']);

        return $this->patch('guest_star/slot_settings', $parameters);
    }
}
