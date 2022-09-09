<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Result;

trait WhispersTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Sends a whisper message to the specified user.
     *
     * NOTE: The user sending the whisper must have a verified phone number (see the Phone Number setting in your Security and Privacy settings).
     *
     * NOTE: The API may silently drop whispers that it suspects of violating Twitch policies.
     * (The API does not indicate that it dropped the whisper; it returns a 204 status code as if it succeeded).
     *
     * Rate Limits: You may whisper to a maximum of 40 unique recipients per day. Within the per day limit,
     * you may whisper a maximum of 3 whispers per second and a maximum of 100 whispers per minute.
     *
     * @see https://dev.twitch.tv/docs/api/reference#send-whisper
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function sendWhisper(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($parameters, ['from_user_id', 'to_user_id']);
        $this->validateRequired($body, ['message']);

        return $this->post('whispers', $parameters, null, $body);
    }
}
