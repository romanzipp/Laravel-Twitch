<?php

namespace romanzipp\Twitch\Tests;

use GuzzleHttp\Psr7\Response;
use romanzipp\Twitch\Result;
use romanzipp\Twitch\Tests\TestCase;

class ResultTest extends TestCase
{
    public function testValidResponseResult()
    {
        $data = ['user' => '123'];

        $response = new Response(200, [], json_encode([
            'data'       => [$data],
            'total'      => 1,
            'pagination' => ['cursor' => 'abc'],
        ]));

        $result = new Result($response);

        $this->assertTrue($result->success());
        $this->assertEquals([(object) $data], $result->data());
        $this->assertEquals((object) $data, $result->shift());
        $this->assertEquals(1, $result->count());
    }

    public function testEmptyResponseResult()
    {
        $response = new Response(200, [], json_encode([
            'data'       => [],
            'total'      => 1,
            'pagination' => ['cursor' => 'abc'],
        ]));

        $result = new Result($response);

        $this->assertTrue($result->success());
        $this->assertEquals([], $result->data());
        $this->assertEquals(null, $result->shift());
        $this->assertEquals(0, $result->count());
    }

    public function testUnexpectedResponseResult()
    {
        $response = new Response(200, [], json_encode([
            'foo' => 'bar',
        ]));

        $result = new Result($response);

        $this->assertTrue($result->success());
        $this->assertEquals([], $result->data());
        $this->assertEquals(null, $result->shift());
        $this->assertEquals(0, $result->count());
    }

    public function testNonJsonResponseResult()
    {
        $response = new Response(200, [], '<title>foo</title>');

        $result = new Result($response);

        $this->assertTrue($result->success());
        $this->assertEquals([], $result->data());
        $this->assertEquals(null, $result->shift());
        $this->assertEquals(0, $result->count());
    }
}
