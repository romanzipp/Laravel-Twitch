<?php

namespace romanzipp\Twitch\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use romanzipp\Twitch\Result;
use romanzipp\Twitch\Tests\TestCases\TestCase;
use romanzipp\Twitch\Twitch;

class ClientExceptionsTest extends TestCase
{
    public function testTransferException()
    {
        $this->expectException(TransferException::class);

        $this->getService(new TransferException)->get('/');
    }

    public function testConnectException()
    {
        $this->expectException(ConnectException::class);

        $this->getService(new ConnectException('Error', new Request('GET', '/')))->get('/');
    }

    public function testClientException()
    {
        $this->expectException(ClientException::class);

        $this->getService(new ClientException('Error', new Request('GET', '/')))->get('/');
    }

    public function testServerException()
    {
        $this->expectException(ServerException::class);

        $this->getService(new ServerException('Error', new Request('GET', '/')))->get('/');
    }

    public function testClientExceptionWithResponse()
    {
        $result = $this->getService(new ClientException('Error', new Request('GET', '/'), new Response(400)))->get('/');

        $this->assertInstanceOf(Result::class, $result);
    }

    public function testServerExceptionWithResponse()
    {
        $result = $this->getService(new ServerException('Error', new Request('GET', '/'), new Response(500)))->get('/');

        $this->assertInstanceOf(Result::class, $result);
    }

    private function getService($mockedResponse): Twitch
    {
        $twitch = new Twitch;

        $twitch->setClientId('foo');

        $twitch->setRequestClient(new Client([
            'handler' => new MockHandler([
                $mockedResponse,
            ]),
        ]));

        return $twitch;
    }
}
