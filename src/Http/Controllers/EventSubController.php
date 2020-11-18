<?php

namespace romanzipp\Twitch\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use romanzipp\Twitch\Events\EventSubHandled;
use romanzipp\Twitch\Events\EventSubReceived;
use romanzipp\Twitch\Http\Middleware\VerifyEventSubSignature;
use Symfony\Component\HttpFoundation\Response;

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
     * @return Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $method = 'handle' . Str::studly(str_replace('.', '_', $payload['type']));

        EventSubReceived::dispatch($payload);

        if (method_exists($this, $method)) {
            $response = $this->{$method}($payload);

            EventSubHandled::dispatch($payload);

            return $response;
        }

        return $this->missingMethod();
    }

    /**
     * Handle a EventSub callback verification call.
     *
     * @param array $payload
     * @return Response
     */
    protected function handleCallbackVerification(array $payload)
    {
        return new Response($payload['challenge'], 200);
    }

    /**
     * Handle a EventSub notification call.
     *
     * @param array $payload
     * @return Response
     */
    protected function handleNotification(array $payload)
    {
        return $this->successMethod();
    }

    /**
     * Handle a EventSub revocation call.
     *
     * @param array $payload
     * @return Response
     */
    protected function handleRevocation(array $payload)
    {
        return $this->successMethod();
    }

    /**
     * Handle successful calls on the controller.
     *
     * @param array $parameters
     * @return Response
     */
    protected function successMethod($parameters = [])
    {
        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param array $parameters
     * @return Response
     */
    protected function missingMethod($parameters = [])
    {
        return new Response;
    }
}
