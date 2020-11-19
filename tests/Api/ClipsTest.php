<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class ClipsTest extends ApiTestCase
{
    public function testCreateClipUnauthenticated()
    {
        $this->registerResult(
            $result = $this->twitch()->createClip(['broadcaster_id' => 29733529])
        );

        self::assertFalse($result->success());
        self::assertEquals(401, $result->getStatus());
    }

    public function testGetClip()
    {
        $this->registerResult(
            $result = $this->twitch()->getClips(['id' => 'BashfulHelpfulSalamanderPrimeMe'])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertHasProperties([
            'id', 'url', 'embed_url', 'broadcaster_id', 'broadcaster_name', 'creator_id', 'creator_name',
            'video_id', 'game_id', 'language', 'title', 'view_count', 'created_at', 'thumbnail_url',
        ], $result->shift());
        self::assertEquals('BashfulHelpfulSalamanderPrimeMe', $result->shift()->id);
        self::assertEquals('Xbox', $result->shift()->broadcaster_name);
    }
}
