<?php

namespace romanzipp\Twitch\Tests;

use GuzzleHttp\Psr7\Response;
use romanzipp\Twitch\Result;
use romanzipp\Twitch\Tests\TestCases\TestCase;

class ResultTest extends TestCase
{
    public function testValidResponseResult()
    {
        $data = ['user' => '123'];

        $response = new Response(200, [], json_encode([
            'data' => [$data],
            'total' => 1,
            'pagination' => ['cursor' => 'abc'],
        ]));

        $result = new Result($response);

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertEquals([(object) $data], $result->data());
        self::assertEquals((object) $data, $result->shift());
        self::assertEquals(1, $result->count());
    }

    public function testEmptyResponseResult()
    {
        $response = new Response(200, [], json_encode([
            'data' => [],
            'total' => 1,
            'pagination' => ['cursor' => 'abc'],
        ]));

        $result = new Result($response);

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertEquals([], $result->data());
        self::assertEquals(null, $result->shift());
        self::assertEquals(0, $result->count());
    }

    public function testUnexpectedResponseResult()
    {
        $response = new Response(200, [], json_encode([
            'foo' => 'bar',
        ]));

        $result = new Result($response);

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertEquals((object) ['foo' => 'bar'], $result->data());
        self::assertEquals(null, $result->shift());
        self::assertEquals(0, $result->count());
    }

    public function testNonJsonResponseResult()
    {
        $response = new Response(200, [], '<title>foo</title>');

        $result = new Result($response);

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertEquals([], $result->data());
        self::assertEquals(null, $result->shift());
        self::assertEquals(0, $result->count());
    }

    public function testProcessDefaultPayload()
    {
        $data = [
            ['user' => 1],
            ['user' => 2],
            ['user' => 3],
        ];

        $response = new Response(200, [], json_encode([
            'total' => 3,
            'data' => $data,
            'pagination' => ['cursor' => 'abc'],
        ]));

        $result = new Result($response);

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertEquals(3, $result->count());

        self::assertEquals(array_map(function ($item) {
            return (object) $item;
        }, $data), $result->data());
    }

    public function testOAuthResponsePayload()
    {
        $data = [
            'access_token' => 'access_token',
            'refresh_token' => 'refresh_token',
            'expires_in' => 10,
            'scope' => ['user:read'],
            'token_type' => 'bearer',
        ];

        $response = new Response(200, [], json_encode($data));

        $result = new Result($response);

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertEquals((object) $data, $result->data());
        self::assertEquals(null, $result->shift());
        self::assertEquals(0, $result->count());
    }
}
