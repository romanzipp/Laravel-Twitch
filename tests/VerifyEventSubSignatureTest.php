<?php

namespace romanzipp\Twitch\Tests;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use romanzipp\Twitch\Http\Middleware\VerifyEventSubSignature;
use romanzipp\Twitch\Objects\EventSubSignature;
use romanzipp\Twitch\Tests\TestCases\TestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VerifyEventSubSignatureTest extends TestCase
{
    protected $request;

    protected $timestamp;

    protected $messageId;

    public function setUp(): void
    {
        parent::setUp();

        config(['twitch-api.eventsub.secret' => 'secret']);
        config(['twitch-api.eventsub.tolerance' => 300]);

        $this->request = new Request([], [], [], [], [], [], 'Signed Body');
        $this->messageId = Str::uuid()->toString();
    }

    public function testResponseIsReceivedWhenSecretMatches(): void
    {
        $this->withTimestamp(time());
        $this->withSignedSignature('secret');

        $response = (new VerifyEventSubSignature())
            ->handle($this->request, function (Request $request) {
                return new Response('OK');
            });

        self::assertEquals('OK', $response->content());
    }

    public function testResponseIsReceivedWhenTimestampIsWithinTolerance(): void
    {
        $this->withTimestamp(time() - 300);
        $this->withSignedSignature('secret');

        $response = (new VerifyEventSubSignature())
            ->handle($this->request, function (Request $request) {
                return new Response('OK');
            });

        self::assertEquals('OK', $response->content());
    }

    public function testAppAbortsWhenTimestampIsOutsideTolerance(): void
    {
        $this->withTimestamp('2021-03-01T20:07:58.288209337Z');
        $this->withSignedSignature('secret');

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Timestamp outside the tolerance zone');

        (new VerifyEventSubSignature())
            ->handle($this->request, static function (Request $request) {
            });
    }

    public function testAppAbortsWhenTimestampIsInvalid(): void
    {
        $this->withTimestamp('invalid');
        $this->withSignedSignature('secret');

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Unable to parse timestamp from header');

        (new VerifyEventSubSignature())
            ->handle($this->request, static function ($request) {
            });
    }

    public function testAppAbortsWhenSecretDoesNotMatch(): void
    {
        $this->withTimestamp(time());
        $this->withSignedSignature('');

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Signature verification failed');

        (new VerifyEventSubSignature())
            ->handle($this->request, static function ($request) {
            });
    }

    public function testWrongFractalsInDateGetSanitized(): void
    {
        self::assertSame(1614629278, EventSubSignature::getTimestamp('2021-03-01T20:07:58.111111111111Z'));
        self::assertSame(1614629278, EventSubSignature::getTimestamp('2021-03-01T20:07:58.1111111111Z'));
        self::assertSame(1614629278, EventSubSignature::getTimestamp('2021-03-01T20:07:58.111111111Z'));
        self::assertSame(1614629278, EventSubSignature::getTimestamp('2021-03-01T20:07:58.11111111Z'));
        self::assertSame(1614629278, EventSubSignature::getTimestamp('2021-03-01T20:07:58.1111111Z'));
        self::assertSame(1614629278, EventSubSignature::getTimestamp('2021-03-01T20:07:58.111111Z'));
        self::assertSame(1614629278, EventSubSignature::getTimestamp('2021-03-01T20:07:58.11111Z'));
        self::assertSame(1614629278, EventSubSignature::getTimestamp('2021-03-01T20:07:58.1111Z'));
        self::assertSame(1614629278, EventSubSignature::getTimestamp('2021-03-01T20:07:58.111Z'));
        self::assertSame(1614629278, EventSubSignature::getTimestamp('2021-03-01T20:07:58.11Z'));
        self::assertSame(1614629278, EventSubSignature::getTimestamp('2021-03-01T20:07:58.1Z'));
    }

    private function withTimestamp($timestamp): void
    {
        if (is_string($timestamp)) {
            $this->timestamp = $timestamp;
        } else {
            $this->timestamp = date('Y-m-d\TH:i:s.u\Z', $timestamp);
        }
    }

    private function withSignedSignature($secret): self
    {
        return $this->withSignature(
            sprintf('sha256=%s', $this->sign($this->request->getContent(), $secret))
        );
    }

    private function withSignature($signature): self
    {
        $this->request->headers->set('Twitch-Eventsub-Message-Id', $this->messageId);
        $this->request->headers->set('Twitch-Eventsub-Message-Timestamp', $this->timestamp);
        $this->request->headers->set('Twitch-Eventsub-Message-Signature', $signature);

        return $this;
    }

    private function sign($payload, $secret): string
    {
        return hash_hmac('sha256', $this->messageId . $this->timestamp . $payload, $secret);
    }
}
