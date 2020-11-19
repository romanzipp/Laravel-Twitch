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

        $this->skipIfClientIdMissing();

        if (null !== self::$lastResult && null !== self::$lastResult->rateLimit() && self::$lastResult->rateLimit('remaining') < 3) {
            self::markTestSkipped(
                sprintf('Rate Limit exceeded (%d/%d)', self::$lastResult->rateLimit('remaining'), self::$lastResult->rateLimit('limit'))
            );
        }

        $this->twitch = app(Twitch::class);

        $this->twitch->setClientId(
            $this->getClientId()
        );

        if ($secret = $this->getClientSecret()) {
            $this->twitch->setClientSecret($secret);
        }
    }

    protected function twitch(): Twitch
    {
        return $this->twitch;
    }

    protected function registerResult(Result $result): Result
    {
        self::assertInstanceOf(Result::class, $result);

        self::$lastResult = $result;

        return $result;
    }

    protected function skipIfClientSecretMissing(): void
    {
        if ($this->getClientSecret()) {
            return;
        }

        self::markTestSkipped('No Client-Secret provided');
    }

    protected function skipIfClientIdMissing(): void
    {
        if ($this->getClientId()) {
            return;
        }

        self::markTestSkipped('No Client-ID provided');
    }
}
