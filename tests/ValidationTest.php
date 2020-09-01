<?php

namespace romanzipp\Twitch\Tests;

use InvalidArgumentException;
use romanzipp\Twitch\Concerns\Validation\ValidationTrait;
use romanzipp\Twitch\Tests\TestCases\TestCase;

class ValidationTest extends TestCase
{
    use ValidationTrait;

    public function testAllRequiredAllMissing()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->validateRequired([], ['foo', 'bar']);
    }

    public function testAllRequiredOneMissing()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->validateRequired(['foo' => 'foo'], ['foo', 'bar']);
    }

    /** @doesNotPerformAssertions */
    public function testAllRequiredAllExisting()
    {
        $this->validateRequired(['foo' => 'foo', 'bar' => 'bar'], ['foo', 'bar']);
    }

    /** @doesNotPerformAssertions */
    public function testAllRequiredMoreExisting()
    {
        $this->validateRequired(['foo' => 'foo', 'bar' => 'bar', 'foobar' => 'foobar'], ['foo', 'bar']);
    }

    public function testOneRequiredMssing()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->validateRequired([], ['foo']);
    }

    public function testOneRequiredEmpty()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->validateRequired(['foo' => null], ['foo']);
    }

    /** @doesNotPerformAssertions */
    public function testOneRequired()
    {
        $this->validateRequired(['foo' => 'bar'], ['foo']);
    }

    // Any

    public function testAnyRequiredMissing()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->validateAnyRequired(['foobar' => 'foo'], ['foo', 'bar']);
    }

    public function testAnyRequiredEmpty()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->validateAnyRequired(['foo' => null], ['foo', 'bar']);
    }

    /** @doesNotPerformAssertions */
    public function testAnyRequiredAllExisting()
    {
        $this->validateAnyRequired(['foo' => 'foo', 'bar' => 'bar'], ['foo', 'bar']);
    }

    /** @doesNotPerformAssertions */
    public function testAnyRequiredMoreExisting()
    {
        $this->validateAnyRequired(['foo' => 'foo', 'bar' => 'bar', 'foobar' => 'foobar'], ['foo', 'bar']);
    }
}
