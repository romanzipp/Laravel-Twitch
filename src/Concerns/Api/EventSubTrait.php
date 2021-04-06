<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait EventSubTrait
{
    use AbstractOperationsTrait;
    use AbstractValidationTrait;

    /**
     * Create a EventSub subscription.
     *
     * @param array $parameters
     * @param array $body
     *
     * @return Result
     */
    public function subscribeEventSub(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, [
            'type',
            'version',
            'condition',
            'transport',
        ]);

        // attach secret, if one has been defined
        if ($secret = config('twitch-api.eventsub.secret')) {
            $body['transport']['secret'] = $secret;
        }

        return $this->post('eventsub/subscriptions', $parameters, null, $body);
    }

    /**
     * Delete a EventSub subscription.
     *
     * @param array $parameters
     *
     * @return Result
     */
    public function unsubscribeEventSub(array $parameters = []): Result
    {
        return $this->delete('eventsub/subscriptions', $parameters);
    }

    /**
     * List your EventSub subscriptions.
     *
     * @param array $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     */
    public function getEventSubs(array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->get('eventsub/subscriptions', $parameters, $paginator);
    }
}
