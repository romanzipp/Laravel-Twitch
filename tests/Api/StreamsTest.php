<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class StreamsTest extends ApiTestCase
{
    public function testGetStreamsPaginated()
    {
        $this->registerResult(
            $firstResult = $this->twitch()->getStreams([])
        );

        self::assertTrue($firstResult->success());
        self::assertTrue($firstResult->hasMoreResults());

        $this->registerResult(
            $nextResult = $this->twitch()->getStreams([], $firstResult->next())
        );

        self::assertTrue($nextResult->success());
        self::assertTrue($nextResult->hasMoreResults());

        self::assertNotEquals($firstResult->getPaginator()->cursor(), $nextResult->getPaginator()->cursor());
    }
}
