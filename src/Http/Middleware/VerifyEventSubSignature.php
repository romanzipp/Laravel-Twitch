<?php

namespace romanzipp\Twitch\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use romanzipp\Twitch\Exceptions\SignatureVerificationException;
use romanzipp\Twitch\Objects\EventSubSignature;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VerifyEventSubSignature
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     *
     * @throws AccessDeniedHttpException
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            EventSubSignature::verifyHeader(
                (string) $request->getContent(),
                $request->headers,
                config('twitch-api.eventsub.secret'),
                config('twitch-api.eventsub.tolerance')
            );
        } catch (SignatureVerificationException $exception) {
            throw new AccessDeniedHttpException($exception->getMessage(), $exception);
        }

        return $next($request);
    }
}
