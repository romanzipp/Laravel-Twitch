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

        $this->assertTrue($firstResult->success());
        $this->assertTrue($firstResult->hasMoreResults());

        $this->registerResult(
            $duplicateFirstResult = $this->twitch()->getStreams([])
        );

        $this->assertTrue($duplicateFirstResult->success());
        $this->assertTrue($duplicateFirstResult->hasMoreResults());

        $this->assertEquals($firstResult->paginator->cursor(), $duplicateFirstResult->paginator->cursor());

        $this->registerResult(
            $nextResult = $this->twitch()->getStreams([], $firstResult->next())
        );

        $this->assertTrue($nextResult->success());
        $this->assertTrue($nextResult->hasMoreResults());

        $this->assertNotEquals($firstResult->paginator->cursor(), $nextResult->paginator->cursor());
    }
}
