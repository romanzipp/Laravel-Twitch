<?php

namespace romanzipp\Twitch\Tests;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Ramsey\Uuid\Uuid;
use romanzipp\Twitch\Enums\EventSubType;
use romanzipp\Twitch\Events\EventSubHandled;
use romanzipp\Twitch\Events\EventSubReceived;
use romanzipp\Twitch\Http\Controllers\EventSubController;
use romanzipp\Twitch\Tests\TestCases\TestCase;

class EventSubControllerTest extends TestCase
{
    public function testProperMethodAreCalledOnEventSubEvent(): void
    {
        $request = $this->request(EventSubType::STREAM_ONLINE);

        Event::fake([
            EventSubHandled::class,
            EventSubReceived::class,
        ]);

        $response = (new EventSubControllerTestStub())->handleWebhook($request);

        Event::assertDispatched(EventSubReceived::class, function (EventSubReceived $event) use ($request) {
            return $request->getContent() === json_encode($event->payload)
                && '1337' === $event->payload['event']['user_id'];
        });

        Event::assertDispatched(EventSubHandled::class, function (EventSubHandled $event) use ($request) {
            return $request->getContent() === json_encode($event->payload)
                && '1337' === $event->payload['event']['user_id'];
        });

        self::assertEquals('Webhook Handled', $response->getContent());
    }

    public function testNormalResponseIsReturnedIfMethodIsMissing(): void
    {
        $request = $this->request(EventSubType::STREAM_OFFLINE);

        Event::fake([
            EventSubHandled::class,
            EventSubReceived::class,
        ]);

        $response = (new EventSubControllerTestStub())->handleWebhook($request);

        Event::assertDispatched(EventSubReceived::class, function (EventSubReceived $event) use ($request) {
            return $request->getContent() === json_encode($event->payload)
                && '1337' === $event->payload['event']['user_id'];
        });

        Event::assertNotDispatched(EventSubHandled::class);

        self::assertEquals(200, $response->getStatusCode());
    }

    private function request(string $event): Request
    {
        $request = Request::create(
            '/',
            'POST',
            [],
            [],
            [],
            [],
            json_encode([
                'subscription' => ['type' => $event],
                'event' => ['user_id' => '1337'],
            ])
        );

        $request->headers->set('twitch-eventsub-message-type', 'notification');
        $request->headers->set('twitch-eventsub-message-id', Uuid::uuid4());
        $request->headers->set('twitch-eventsub-message-retry', 0);
        $request->headers->set('twitch-eventsub-message-timestamp', Carbon::now()->toIso8601ZuluString());

        return $request;
    }
}

class EventSubControllerTestStub extends EventSubController
{
    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        // Don't call parent constructor to prevent setting middleware...
    }

    public function handleStreamOnlineNotification(array $payload): Response
    {
        return new Response('Webhook Handled', 200);
    }
}
