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

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
        self::assertHasProperties(['id', 'name', 'box_art_url'], $result->shift());
    }

    public function testGetGameById()
    {
        $this->registerResult(
            $result = $this->twitch()->getGames(['id' => 511224])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
        self::assertEquals(511224, (int) $result->shift()->id);
        self::assertEquals('Apex Legends', $result->shift()->name);
    }

    public function testGetGameByName()
    {
        $this->registerResult(
            $result = $this->twitch()->getGames(['name' => 'Apex Legends'])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
        self::assertEquals(511224, (int) $result->shift()->id);
        self::assertEquals('Apex Legends', $result->shift()->name);
    }

    public function testGetGamesByIds()
    {
        $this->registerResult(
            $result = $this->twitch()->getGames(['id' => [488552, 511224]])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());

        $ids = array_map(function (stdClass $game) {
            return (int) $game->id;
        }, $result->data());

        sort($ids);

        self::assertEquals([488552, 511224], $ids);
    }

    public function testGetGamesByNames()
    {
        $this->registerResult(
            $result = $this->twitch()->getGames(['name' => ['Overwatch', 'Apex Legends']])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());

        $ids = array_map(function (stdClass $game) {
            return (int) $game->id;
        }, $result->data());

        sort($ids);

        self::assertEquals([488552, 511224], $ids);
    }

    public function testGetGamesWithPagination()
    {
        $this->registerResult(
            $firstResult = $this->twitch()->getTopGames()
        );

        self::assertTrue($firstResult->success());
        self::assertNotEmpty($firstResult->data());

        $first = $firstResult->data()[0];

        $this->registerResult(
            $secondResult = $this->twitch()->getTopGames([], $firstResult->next())
        );

        self::assertTrue($secondResult->success());
        self::assertNotEmpty($secondResult->data());

        $second = $secondResult->data()[0];

        self::assertNotEquals($first->id, $second->id);

        $this->registerResult(
            $thirdResult = $this->twitch()->getTopGames([], $secondResult->back())
        );

        self::assertTrue($thirdResult->success());
        self::assertNotEmpty($thirdResult->data());

        $third = $thirdResult->data()[0];

        self::assertEquals($first->id, $third->id);
    }
}
