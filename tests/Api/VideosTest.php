<?php

namespace romanzipp\Twitch\Tests\Api;

use InvalidArgumentException;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class VideosTest extends ApiTestCase
{
    public function testGetVideosMissingParameters()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->twitch()->getVideos([]);
    }

    public function testGetVideosById()
    {
        $this->registerResult(
            $result = $this->twitch()->getVideos(['id' => 327720797])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
        self::assertHasProperties([
            'id', 'user_id', 'user_name', 'title', 'description', 'created_at', 'published_at', 'url',
            'thumbnail_url', 'viewable', 'view_count', 'language', 'type', 'duration',
        ], $result->shift());
        self::assertEquals(327720797, (int) $result->shift()->id);
    }

    public function testGetVideosByUser()
    {
        $this->registerResult(
            $result = $this->twitch()->getVideos(['user_id' => 12826])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
        self::assertHasProperties([
            'id', 'user_id', 'user_name', 'title', 'description', 'created_at', 'published_at', 'url',
            'thumbnail_url', 'viewable', 'view_count', 'language', 'type', 'duration',
        ], $result->shift());
        self::assertEquals(12826, (int) $result->shift()->user_id);
    }

    public function testGetVideosByGame()
    {
        $this->registerResult(
            $result = $this->twitch()->getVideos(['game_id' => 511224])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
        self::assertHasProperties([
            'id', 'user_id', 'user_name', 'title', 'description', 'created_at', 'published_at', 'url',
            'thumbnail_url', 'viewable', 'view_count', 'language', 'type', 'duration',
        ], $result->shift());
        // Video payload has no game_id ?!?!?!?!?!?!?!
    }
}
