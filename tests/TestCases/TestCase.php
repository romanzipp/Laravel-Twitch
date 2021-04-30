<?php

namespace romanzipp\Twitch\Tests\TestCases;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use Orchestra\Testbench\TestCase as BaseTestCase;
use romanzipp\Twitch\Facades\Twitch as TwitchFacade;
use romanzipp\Twitch\Providers\TwitchServiceProvider;
use romanzipp\Twitch\Twitch;
use stdClass;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            TwitchServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Twitch' => TwitchFacade::class,
        ];
    }

    protected function getClientSecret()
    {
        return getenv('CLIENT_SECRET');
    }

    protected function getClientId()
    {
        return getenv('CLIENT_ID');
    }

    protected function getToken()
    {
        return getenv('ACCESS_TOKEN');
    }

    protected function getBroadcasterId()
    {
        return getenv('BROADCASTER_ID');
    }

    protected function getMockedService($mockedResponse): Twitch
    {
        $twitch = new Twitch();

        $twitch->setClientId('foo');

        $twitch->setRequestClient(new Client([
            'handler' => new MockHandler([
                $mockedResponse,
            ]),
        ]));

        return $twitch;
    }

    protected static function assertHasProperty(string $property, stdClass $object): void
    {
        static::assertThat(property_exists($object, $property), static::isTrue(), 'Asserting that an object has a property');
    }

    protected static function assertHasProperties(array $properties, stdClass $object): void
    {
        foreach ($properties as $property) {
            self::assertHasProperty($property, $object);
        }
    }
}
