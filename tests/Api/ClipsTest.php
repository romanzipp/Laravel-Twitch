<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class ClipsTest extends ApiTestCase
{
    public function testCreateClipUnauthenticated()
    {
        $this->registerResult(
            $result = $this->twitch()->createClip(29733529)
        );

        $this->assertFalse($result->success());
        $this->assertEquals(401, $result->status());
    }

    public function testGetClip()
    {
        $this->registerResult(
            $result = $this->twitch()->getClip('BashfulHelpfulSalamanderPrimeMe')
        );

        $this->assertTrue($result->success());
        $this->assertHasProperties([
            'id', 'url', 'embed_url', 'broadcaster_id', 'broadcaster_name', 'creator_id', 'creator_name',
            'video_id', 'game_id', 'language', 'title', 'view_count', 'created_at', 'thumbnail_url',
        ], $result->shift());
        $this->assertEquals('BashfulHelpfulSalamanderPrimeMe', $result->shift()->id);
        $this->assertEquals('Xbox', $result->shift()->broadcaster_name);
    }
}
