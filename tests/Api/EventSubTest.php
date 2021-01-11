<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Enums\EventSubType;
use romanzipp\Twitch\Result;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class EventSubTest extends ApiTestCase
{
    public function testSubscribeEventSub()
    {
        self::markTestSkipped('Investigate: Github actions will run the tests in a parallel matrix causing collisions');

        $this->unsubscribe();

        $this->registerResult(
            $result = $this->subscribe()
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
        self::assertHasProperties([
            'id', 'status', 'type', 'version', 'condition', 'created_at', 'transport',
        ], $result->shift());

        $this->unsubscribe(true);
    }

    public function testGetWebhooks()
    {
        self::markTestSkipped('Investigate: Github actions will run the tests in a parallel matrix causing collisions');

        $this->unsubscribe();
        $this->subscribe();

        $this->registerResult(
            $result = $this->twitch()->getEventSubs([])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
        self::assertHasProperties([
            'id', 'status', 'type', 'version', 'condition', 'created_at', 'transport',
        ], $result->shift());

        $this->unsubscribe(true);
    }

    private function subscribe(): Result
    {
        return $this->twitch()->subscribeEventSub([], [
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
        ]);
    }

    private function unsubscribe(bool $expectUnsubscribed = false): void
    {
        $result = $this->twitch()->getEventSubs([]);

        self::assertTrue($result->success(), $result->getErrorMessage());

        if ($expectUnsubscribed) {
            self::assertNotEmpty($result->data());
            self::assertHasProperties([
                'id',
            ], $result->shift());
        }

        foreach ($result->data() as $data) {
            $unsubResult = $this->twitch()->unsubscribeEventSub([
                'id' => $data->id,
            ]);

            self::assertTrue($unsubResult->success(), $unsubResult->getErrorMessage());
        }
    }
}
