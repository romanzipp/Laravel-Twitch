<?php

namespace romanzipp\Twitch\Tests;

use GuzzleHttp\Psr7\Response;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;
use romanzipp\Twitch\Tests\TestCases\TestCase;

class PaginationTest extends TestCase
{
    public function testResultPaginatorInstance()
    {
        $response = new Response(200, [], json_encode([
            'total' => 1,
            'data' => [['user' => 1]],
            'pagination' => [
                'cursor' => 'abc123',
            ],
        ]));

        $result = new Result($response);

        self::assertInstanceOf(Paginator::class, $result->getPaginator());
        self::assertInstanceOf(Paginator::class, $result->next());
        self::assertInstanceOf(Paginator::class, $result->back());
    }

    public function testPaginatorActions()
    {
        $response = new Response(200, [], json_encode([
            'total' => 1,
            'data' => [['user' => 1]],
            'pagination' => [
                'cursor' => 'abc123',
            ],
        ]));

        $result = new Result($response);

        self::assertInstanceOf(Paginator::class, $result->getPaginator());

        self::assertEquals('after', $result->next()->action);
        self::assertEquals('before', $result->back()->action);
    }

    public function testContinuosNextPagination()
    {
        $firstService = $this->getMockedService(
            new Response(200, [], json_encode([
                'total' => 1,
                'data' => [['user' => 1]],
                'pagination' => [
                    'cursor' => 'abc123',
                ],
            ]))
        );

        $firstResult = $firstService->getStreams([]);

        self::assertInstanceOf(Paginator::class, $firstResult->getPaginator());
        self::assertTrue($firstResult->hasMoreResults());

        $service = $this->getMockedService(
            new Response(200, [], json_encode([
                'total' => 1,
                'data' => [['user' => 2]],
                'pagination' => [
                    'cursor' => 'abc123',
                ],
            ]))
        );

        $secondResult = $service->getStreams([], $firstResult->next());

        self::assertInstanceOf(Paginator::class, $secondResult->getPaginator());
        self::assertTrue($secondResult->hasMoreResults());
    }

    public function testEmptyPagination()
    {
        $firstService = $this->getMockedService(
            new Response(200, [], json_encode([
                'total' => 1,
                'data' => [['user' => 1]],
                'pagination' => [
                    'cursor' => 'abc123',
                ],
            ]))
        );

        $firstResult = $firstService->getStreams([]);

        self::assertInstanceOf(Paginator::class, $firstResult->getPaginator());
        self::assertTrue($firstResult->hasMoreResults());

        $service = $this->getMockedService(
            new Response(200, [], json_encode([
                'total' => 1,
                'data' => [['user' => 2]],
                'pagination' => (object) [],
            ]))
        );

        $secondResult = $service->getStreams([], $firstResult->next());

        self::assertInstanceOf(Paginator::class, $secondResult->getPaginator());
        self::assertFalse($secondResult->hasMoreResults());
    }

    public function testNullPagination()
    {
        $firstService = $this->getMockedService(
            new Response(200, [], json_encode([
                'total' => 1,
                'data' => [['user' => 1]],
                'pagination' => [
                    'cursor' => 'abc123',
                ],
            ]))
        );

        $firstResult = $firstService->getStreams([]);

        self::assertInstanceOf(Paginator::class, $firstResult->getPaginator());
        self::assertTrue($firstResult->hasMoreResults());

        $service = $this->getMockedService(
            new Response(200, [], json_encode([
                'total' => 1,
                'data' => [['user' => 2]],
                'pagination' => null,
            ]))
        );

        $secondResult = $service->getStreams([], $firstResult->next());

        self::assertInstanceOf(Paginator::class, $secondResult->getPaginator());
        self::assertFalse($secondResult->hasMoreResults());
    }

    public function testMissingPagination()
    {
        $firstService = $this->getMockedService(
            new Response(200, [], json_encode([
                'total' => 1,
                'data' => [['user' => 1]],
                'pagination' => [
                    'cursor' => 'abc123',
                ],
            ]))
        );

        $firstResult = $firstService->getStreams([]);

        self::assertInstanceOf(Paginator::class, $firstResult->getPaginator());
        self::assertTrue($firstResult->hasMoreResults());

        $service = $this->getMockedService(
            new Response(200, [], json_encode([
                'total' => 1,
                'data' => [['user' => 2]],
            ]))
        );

        $secondResult = $service->getStreams([], $firstResult->next());

        self::assertInstanceOf(Paginator::class, $secondResult->getPaginator());
        self::assertFalse($secondResult->hasMoreResults());
    }
}
