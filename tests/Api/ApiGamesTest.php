<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class ApiGamesTest extends ApiTestCase
{
    public function testNextPagination()
    {
        $this->registerResult(
            $result = $this->twitch()->getTopGames()
        );

        $this->assertTrue($result->success);

        $first = $result->data()[0];

        $this->registerResult(
            $result = $this->twitch()->getTopGames([], $result->next())
        );

        $this->assertTrue($result->success);

        $second = $result->data()[0];

        $this->assertNotEquals($first->id, $second->id);

        $this->registerResult(
            $result = $this->twitch()->getTopGames([], $result->back())
        );

        $this->assertTrue($result->success);

        $third = $result->data()[0];

        $this->assertEquals($first->id, $third->id);
    }
}
