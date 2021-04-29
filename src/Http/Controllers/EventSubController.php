<?php

namespace romanzipp\Twitch\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use romanzipp\Twitch\Events\EventSubHandled;
use romanzipp\Twitch\Events\EventSubReceived;
use romanzipp\Twitch\Http\Middleware\VerifyEventSubSignature;
use romanzipp\Twitch\Objects\EventSubSignature;

class EventSubController extends Controller
{
    public function __construct()
    {
        if (config('twitch-api.eventsub.secret')) {
            $this->middleware(VerifyEventSubSignature::class);
        }
    }

    /**
     * Handle a Twitch webhook call.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handleWebhook(Request $request): Response
    {
        $payload = json_decode($request->getContent(), true);

        $messageType = $request->header('twitch-eventsub-message-type');
        $messageId = $request->header('twitch-eventsub-message-id');
        $retries = (int) $request->header('twitch-eventsub-message-retry');
        $timestamp = Carbon::createFromTimestampUTC(
                        EventSubSignature::getTimestamp($request->header('twitch-eventsub-message-timestamp')));

        if ('notification' === $messageType) {
            $messageType = sprintf('%s.notification', $payload['subscription']['type']);
        }

        $method = 'handle' . Str::studly(str_replace('.', '_', $messageType));

        EventSubReceived::dispatch($payload, $messageId, $retries, $timestamp);

        if (method_exists($this, $method)) {
            $response = $this->{$method}($payload);

            EventSubHandled::dispatch($payload, $messageId, $retries, $timestamp);

            return $response;
        }

        return $this->missingMethod();
    }

    /**
     * Handle a EventSub callback verification call.
     *
     * @param array $payload
     *
     * @return Response
     */
    protected function handleWebhookCallbackVerification(array $payload): Response
    {
        return new Response($payload['challenge'], 200);
    }

    /**
     * Handle a EventSub notification call.
     *
     * @param array $payload
     *
     * @return Response
     */
    protected function handleNotification(array $payload): Response
    {
        return $this->successMethod();
    }

    /**
     * Handle a EventSub revocation call.
     *
     * @param array $payload
     *
     * @return Response
     */
    protected function handleRevocation(array $payload): Response
    {
        return $this->successMethod();
    }

    /**
     * Handle successful calls on the controller.
     *
     * @param array $parameters
     *
     * @return Response
     */
    protected function successMethod($parameters = []): Response
    {
        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param array $parameters
     *
     * @return Response
     */
    protected function missingMethod($parameters = []): Response
    {
        return new Response();
    }
}
