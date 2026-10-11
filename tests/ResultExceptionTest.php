<?php

namespace romanzipp\Twitch\Tests;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use romanzipp\Twitch\Result;
use romanzipp\Twitch\Tests\TestCases\TestCase;

class ResultExceptionTest extends TestCase
{
    public function testClientExceptionEmptyBody()
    {
        $request = new Request('GET', '/');

        $response = new Response(404, []);

        $result = new Result($response, new ClientException('Not Found', $request, $response));

        self::assertFalse($result->success());
        self::assertEquals('Not Found', $result->getErrorMessage());
    }

    public function testClientExceptionMissingMessage()
    {
        $request = new Request('GET', '/');

        $response = new Response(404, [], json_encode([
            'data' => [],
        ]));

        $result = new Result($response, new ClientException('Not Found', $request, $response));

        self::assertFalse($result->success());
        self::assertEquals('Not Found', $result->getErrorMessage());
    }

    public function testClientExceptionNullMessage()
    {
        $request = new Request('GET', '/');

        $response = new Response(404, [], json_encode([
            'data' => [],
            'message' => null,
        ]));

        $result = new Result($response, new ClientException('Not Found', $request, $response));

        self::assertFalse($result->success());
        self::assertEquals('Not Found', $result->getErrorMessage());
    }

    public function testClientExceptionWithMessage()
    {
        $request = new Request('GET', '/');

        $response = new Response(404, [], json_encode([
            'data' => [],
            'message' => 'No Data',
        ]));

        $result = new Result($response, new ClientException('Not Found', $request, $response));

        self::assertFalse($result->success());
        self::assertEquals('No Data', $result->getErrorMessage());
    }
}
