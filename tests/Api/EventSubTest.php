<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Enums\EventSubType;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class EventSubTest extends ApiTestCase
{
    /**
     * First test: Cleanup existing EventSubs, to prevent false positives.
     */
    public function testUnsubscribeAllEventSubs(): void
    {
        $this->registerResult(
            $result = $this->twitch()->getEventSubs([])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());

        foreach ($result->data() as $data) {
            $this->registerResult(
                $result = $this->twitch()->unsubscribeEventSub([
                    'id' => $data->id,
                ])
            );

            self::assertTrue($result->success(), $result->getErrorMessage());
        }
    }

    /**
     * Second test: Subscribe a EventSub.
     *
     * @depends testUnsubscribeAllEventSubs
     */
    public function testSubscribeEventSub(): void
    {
        $this->registerResult(
            $result = $this->twitch()->subscribeEventSub([], [
                'type' => EventSubType::STREAM_ONLINE,
                'version' => '1',
                'condition' => [
                    'broadcaster_user_id' => '1337',
                ],
                'transport' => [
                    'method' => 'webhook',
                    'callback' => 'https://example.com/webhooks/callback',
                    'secret' => 'must-be-at-least-10-characters',
                ],
            ])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
        self::assertHasProperties([
            'id', 'status', 'type', 'version', 'condition', 'created_at', 'transport',
        ], $result->shift());
    }

    /**
     * Third test: Get EventSubs to check they actual payload.
     *
     * @depends testSubscribeEventSub
     */
    public function testGetWebhooks(): void
    {
        $this->registerResult(
            $result = $this->twitch()->getEventSubs([])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
        self::assertHasProperties([
            'id', 'status', 'type', 'version', 'condition', 'created_at', 'transport',
        ], $result->shift());
    }

    /**
     * Forth test: Cleanup all our test mess. This will also tests unsubscribe if the first test has no results.
     *
     * @depends testSubscribeEventSub
     */
    public function testUnsubscribeEventSub(): void
    {
        $this->registerResult(
            $result = $this->twitch()->getEventSubs([])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
        self::assertHasProperties([
            'id',
        ], $result->shift());

        foreach ($result->data() as $data) {
            $this->registerResult(
                $result = $this->twitch()->unsubscribeEventSub([
                    'id' => $data->id,
                ])
            );

            self::assertTrue($result->success(), $result->getErrorMessage());
        }
    }
}
