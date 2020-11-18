<?php

namespace romanzipp\Twitch\Tests;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use romanzipp\Twitch\Http\Middleware\VerifyEventSubSignature;
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
        $this->withTimestamp(time() - 301);
        $this->withSignedSignature('secret');

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Timestamp outside the tolerance zone');

        (new VerifyEventSubSignature())
            ->handle($this->request, static function (Request $request) {
                //
            });
    }

    public function testAppAbortsWhenTimestampIsInvalid(): void
    {
        $this->withTimestamp('invalid');
        $this->withSignedSignature('secret');

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Unable to extract timestamp and signatures from header');

        (new VerifyEventSubSignature())
            ->handle($this->request, static function ($request) {
                //
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
                //
            });
    }

    private function withTimestamp($timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    private function withSignedSignature($secret): self
    {
        return $this->withSignature(
            $this->sign($this->request->getContent(), $secret)
        );
    }

    private function withSignature($signature): self
    {
        $this->request->headers->set('Twitch-Eventsub-Message-Id', $this->messageId);
        $this->request->headers->set('Twitch-Eventsub-Message-Timestamp', $this->timestamp);
        $this->request->headers->set('Twitch-Eventsub-Message-Signature', $signature);

        return $this;
    }

    private function sign($payload, $secret)
    {
        return hash_hmac('sha256', $this->messageId . $this->timestamp . $payload, $secret);
    }
}
