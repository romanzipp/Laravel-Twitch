<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Facades\Twitch;
use romanzipp\Twitch\Result;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class ApiGamesTest extends ApiTestCase
{
    public function testNextPagination()
    {
        $this->registerResult($result = Twitch::getTopGames());

        $first = $result->data()[0];

        $this->assertInstanceOf(Result::class, $result);

        $this->registerResult($result = Twitch::getTopGames([], $result->next()));

        $second = $result->data()[0];

        $this->assertNotEquals($first->id, $second->id);

        $this->registerResult($result = Twitch::getTopGames([], $result->back()));

        $third = $result->data()[0];

        $this->assertEquals($first->id, $third->id);
    }
}
