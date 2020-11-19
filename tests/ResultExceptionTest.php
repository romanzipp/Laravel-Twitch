<?php

namespace romanzipp\Twitch\Tests;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use romanzipp\Twitch\Result;
use romanzipp\Twitch\Tests\TestCases\TestCase;

class ResultExceptionTest extends TestCase
{
    public function testRequestExceptionEmptyBody()
    {
        $request = new Request('GET', '/');

        $response = new Response(404, []);

        $result = new Result($response, new RequestException('Not Found', $request, $response));

        self::assertFalse($result->success());
        self::assertEquals('Not Found', $result->error());
    }

    public function testRequestExceptionMissingMessage()
    {
        $request = new Request('GET', '/');

        $response = new Response(404, [], json_encode([
            'data' => [],
        ]));

        $result = new Result($response, new RequestException('Not Found', $request, $response));

        self::assertFalse($result->success());
        self::assertEquals('Not Found', $result->error());
    }

    public function testRequestExceptionNullMessage()
    {
        $request = new Request('GET', '/');

        $response = new Response(404, [], json_encode([
            'data' => [],
            'message' => null,
        ]));

        $result = new Result($response, new RequestException('Not Found', $request, $response));

        self::assertFalse($result->success());
        self::assertEquals('Not Found', $result->error());
    }

    public function testRequestExceptionWithMessage()
    {
        $request = new Request('GET', '/');

        $response = new Response(404, [], json_encode([
            'data' => [],
            'message' => 'No Data',
        ]));

        $result = new Result($response, new RequestException('Not Found', $request, $response));

        self::assertFalse($result->success());
        self::assertEquals('No Data', $result->error());
    }

    public function testRequestExceptionMalformedMessage()
    {
        $request = new Request('GET', '/');

        $response = new Response(404, [], json_encode([
            'data' => [],
            'message' => ['No Data'],
        ]));

        $result = new Result($response, new RequestException('Not Found', $request, $response));

        self::assertFalse($result->success());
        self::assertEquals('Not Found', $result->error());
    }
}
