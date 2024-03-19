<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait ScheduleTrait
{
    use AbstractOperationsTrait;
    use AbstractValidationTrait;

    /**
     * Gets all scheduled broadcasts or specific scheduled broadcasts from a channel’s stream schedule.
     * Scheduled broadcasts are defined as “stream segments” in the API.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-channel-stream-schedule
     *
     * @param array<string, mixed> $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     */
    public function getChannelStreamSchedule(array $parameters = [], ?Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('schedule', $parameters, $paginator);
    }

    /**
     * Gets all scheduled broadcasts from a channel’s stream schedule as an iCalendar.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-channel-icalendar
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result -- iCalendar data is returned according to RFC5545. The expected MIME type is text/calendar.
     */
    public function getChanneliCalendar(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('schedule/icalendar', $parameters);
    }

    /**
     * Update the settings for a channel’s stream schedule. This can be used for setting vacation details.
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-channel-stream-schedule
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function updateChannelStreamSchedule(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->patch('schedule/settings', $parameters);
    }

    /**
     * Create a single scheduled broadcast or a recurring scheduled broadcast for a channel’s stream schedule.
     *
     * @see https://dev.twitch.tv/docs/api/reference#create-channel-stream-schedule-segment
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function createChannelStreamScheduleSegment(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        $this->validateRequired($body, ['start_time', 'timezone', 'duration']);

        return $this->post('schedule/segment', $parameters, null, $body);
    }

    /**
     * Update a single scheduled broadcast or a recurring scheduled broadcast for a channel’s stream schedule.
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-channel-stream-schedule-segment
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function updateChannelStreamScheduleSegment(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'id']);

        return $this->patch('schedule/segment', $parameters, null, $body);
    }

    /**
     * Delete a single scheduled broadcast or a recurring scheduled broadcast for a channel’s stream schedule.
     *
     * @see https://dev.twitch.tv/docs/api/reference#delete-channel-stream-schedule-segment
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function deleteChannelStreamScheduleSegment(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'id']);
        $this->validateRequired($body, ['broadcaster_id', 'id']);

        return $this->delete('schedule/segment', $parameters, null, $body);
    }
}
