<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Enums\PollStatus;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class PollsTest extends ApiTestCase
{
    public function testGetPolls()
    {
        $this->skipIfTokenMissing();
        $this->skipIfBroadcasterIdMissing();

        $this->registerResult(
            $result = $this->twitch()->withToken($this->getToken())->getPolls([
                'broadcaster_id' => $this->getBroadcasterId(),
            ])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
    }

    public function testCreatePolls()
    {
        $this->skipIfTokenMissing();
        $this->skipIfBroadcasterIdMissing();

        $this->registerResult(
            $result = $this->twitch()->withToken($this->getToken())->createPoll([], [
                'broadcaster_id' => $this->getBroadcasterId(),
                'title' => 'Hello World!',
                'choices' => [
                    [
                        'title' => 'A eSports',
                    ],
                    [
                        'title' => 'B eSports',
                    ],
                ],
                'duration' => 30,
            ])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
    }

    /**
     * @depends testCreatePolls
     */
    public function testEndPoll()
    {
        $this->skipIfTokenMissing();
        $this->skipIfBroadcasterIdMissing();

        $poll = $this->twitch()->withToken($this->getToken())->getPolls([
            'broadcaster_id' => $this->getBroadcasterId(),
        ])->shift();

        if ( ! $poll) {
            self::markTestSkipped('No Poll provided');
        }

        $this->registerResult(
            $result = $this->twitch()->endPoll([], [
                'broadcaster_id' => '106415581',
                'id' => $poll->id,
                'status' => PollStatus::TERMINATED,
            ])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
    }
}
