<?php

namespace romanzipp\Twitch\Tests\TestCases;

use Orchestra\Testbench\TestCase as BaseTestCase;
use romanzipp\Twitch\Facades\Twitch;
use romanzipp\Twitch\Providers\TwitchServiceProvider;
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
            'Twitch' => Twitch::class,
        ];
    }

    public function assertHasProperty(string $property, stdClass $object): void
    {
        static::assertThat(property_exists($object, $property), static::isTrue(), 'Asserting that an object has a property');
    }

    public function assertHasProperties(array $properties, stdClass $object): void
    {
        foreach ($properties as $property) {
            $this->assertHasProperty($property, $object);
        }
    }
}
