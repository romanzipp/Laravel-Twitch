<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Tests\TestCases\ApiTestCase;
use stdClass;

class GamesTest extends ApiTestCase
{
    public function testGetGames()
    {
        $this->registerResult(
            $result = $this->twitch()->getTopGames()
        );

        $this->assertTrue($result->success());
        $this->assertNotEmpty($result->data());
        $this->assertHasProperties(['id', 'name', 'box_art_url'], $result->shift());
    }

    public function testGetGameById()
    {
        $this->registerResult(
            $result = $this->twitch()->getGameById(511224)
        );

        $this->assertTrue($result->success());
        $this->assertNotEmpty($result->data());
        $this->assertEquals(511224, (int) $result->shift()->id);
        $this->assertEquals('Apex Legends', $result->shift()->name);
    }

    public function testGetGameByName()
    {
        $this->registerResult(
            $result = $this->twitch()->getGameByName('Apex Legends')
        );

        $this->assertTrue($result->success());
        $this->assertNotEmpty($result->data());
        $this->assertEquals(511224, (int) $result->shift()->id);
        $this->assertEquals('Apex Legends', $result->shift()->name);
    }

    public function testGetGamesByIds()
    {
        $this->registerResult(
            $result = $this->twitch()->getGamesByIds([488552, 511224])
        );

        $this->assertTrue($result->success());
        $this->assertNotEmpty($result->data());

        $ids = array_map(function (stdClass $game) {
            return (int) $game->id;
        }, $result->data());

        sort($ids);

        $this->assertEquals([488552, 511224], $ids);
    }

    public function testGetGamesByNames()
    {
        $this->registerResult(
            $result = $this->twitch()->getGamesByNames(['Overwatch', 'Apex Legends'])
        );

        $this->assertTrue($result->success());
        $this->assertNotEmpty($result->data());

        $ids = array_map(function (stdClass $game) {
            return (int) $game->id;
        }, $result->data());

        sort($ids);

        $this->assertEquals([488552, 511224], $ids);
    }

    public function testGetGamesWithPagination()
    {
        $this->registerResult(
            $firstResult = $this->twitch()->getTopGames()
        );

        $this->assertTrue($firstResult->success());
        $this->assertNotEmpty($firstResult->data());

        $first = $firstResult->data()[0];

        $this->registerResult(
            $secondResult = $this->twitch()->getTopGames([], $firstResult->next())
        );

        $this->assertTrue($secondResult->success());
        $this->assertNotEmpty($secondResult->data());

        $second = $secondResult->data()[0];

        $this->assertNotEquals($first->id, $second->id);

        $this->registerResult(
            $thirdResult = $this->twitch()->getTopGames([], $secondResult->back())
        );

        $this->assertTrue($thirdResult->success());
        $this->assertNotEmpty($thirdResult->data());

        $third = $thirdResult->data()[0];

        $this->assertEquals($first->id, $third->id);
    }
}
