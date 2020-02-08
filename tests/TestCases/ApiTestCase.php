<?php

namespace romanzipp\Twitch\Tests\TestCases;

use romanzipp\Twitch\Result;
use romanzipp\Twitch\Twitch;

abstract class ApiTestCase extends TestCase
{
    /**
     * @var Result|null
     */
    protected static $lastResult = null;

    /**
     * @var Twitch
     */
    protected $twitch;

    protected function setUp(): void
    {
        parent::setUp();

        if ( ! $this->getClientId()) {
            $this->markTestSkipped('No Client-ID given');
        }

        if (self::$lastResult !== null && self::$lastResult->rateLimit() !== null && self::$lastResult->rateLimit('remaining') < 3) {
            $this->markTestSkipped(
                sprintf('Rate Limit exceeded (%d/%d)', self::$lastResult->rateLimit('remaining'), self::$lastResult->rateLimit('limit'))
            );
        }

        $this->twitch = app(Twitch::class);
        $this->twitch->setClientId(
            $this->getClientId()
        );
    }

    protected function twitch(): Twitch
    {
        return $this->twitch;
    }

    protected function registerResult(Result $result): Result
    {
        $this->assertInstanceOf(Result::class, $result);

        self::$lastResult = $result;

        return $result;
    }

    protected function getClientId()
    {
        return getenv('CLIENT_ID');
    }
}
