<?php

namespace romanzipp\Twitch\Tests;

use GuzzleHttp\Psr7\Response;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;
use romanzipp\Twitch\Tests\TestCases\TestCase;

class PaginationTest extends TestCase
{
    public function testInstance()
    {
        $response = new Response(200, [], json_encode([
            'total'      => 3,
            'data'       => [
                ['user' => 1],
                ['user' => 2],
                ['user' => 3],
            ],
            'pagination' => [
                'cursor' => 'abc123',
            ],
        ]));

        $result = new Result($response);

        $this->assertInstanceOf(Paginator::class, $result->paginator);

        $this->assertInstanceOf(Paginator::class, $result->first());

        $this->assertInstanceOf(Paginator::class, $result->next());

        $this->assertInstanceOf(Paginator::class, $result->back());
    }

    public function testActions()
    {
        $response = new Response(200, [], json_encode([
            'total'      => 3,
            'data'       => [
                ['user' => 1],
                ['user' => 2],
                ['user' => 3],
            ],
            'pagination' => [
                'cursor' => 'abc123',
            ],
        ]));

        $result = new Result($response);

        $this->assertInstanceOf(Paginator::class, $result->paginator);

        $this->assertEquals('first', $result->first()->action);

        $this->assertEquals('after', $result->next()->action);

        $this->assertEquals('before', $result->back()->action);
    }
}
