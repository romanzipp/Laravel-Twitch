<?php

namespace romanzipp\Twitch\Tests;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use romanzipp\Twitch\Result;
use romanzipp\Twitch\Tests\TestCases\TestCase;

class ClientExceptionsTest extends TestCase
{
    public function testTransferException()
    {
        $this->expectException(TransferException::class);

        $this->getMockedService(new TransferException())->get('/');
    }

    public function testConnectException()
    {
        $this->expectException(ConnectException::class);

        $this
            ->getMockedService(
                new ConnectException('Error', new Request('GET', '/'))
            )
            ->get('/');
    }

    public function testClientExceptionWithResponse()
    {
        $result = $this
            ->getMockedService(
                new ClientException('Error', new Request('GET', '/'), new Response(400))
            )
            ->get('/');

        self::assertInstanceOf(Result::class, $result);
    }

    public function testServerExceptionWithResponse()
    {
        $result = $this
            ->getMockedService(
                new ServerException('Error', new Request('GET', '/'), new Response(500))
            )
            ->get('/');

        self::assertInstanceOf(Result::class, $result);
    }
}
