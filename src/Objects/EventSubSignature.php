<?php

namespace romanzipp\Twitch\Objects;

use Carbon\Carbon;
use Exception;
use romanzipp\Twitch\Exceptions\SignatureVerificationException;
use Symfony\Component\HttpFoundation\HeaderBag;

class EventSubSignature
{
    /**
     * Verifies the signature header sent by Twitch. Throws an SignatureVerificationException
     * exception if the verification fails for any reason.
     *
     * @param string $payload the payload sent by Twitch
     * @param HeaderBag $headers the contents of the signature header sent by Twitch
     * @param string $secret secret used to generate the signature
     * @param int $tolerance maximum difference allowed between the header's timestamp and the current time
     *
     * @throws SignatureVerificationException if the verification fails
     *
     * @return void
     */
    public static function verifyHeader(string $payload, HeaderBag $headers, string $secret, int $tolerance = 600): void
    {
        $rawTimestamp = $headers->get('twitch-eventsub-message-timestamp');

        try {
            $timestamp = self::getTimestamp($rawTimestamp);
        } catch (Exception $exception) {
            throw new SignatureVerificationException('Unable to parse timestamp from header', 0, $exception);
        }

        if (! is_numeric($timestamp)) {
            throw new SignatureVerificationException('Unable to extract timestamp from header');
        }

        if ($tolerance > 0 && abs(Carbon::now('UTC')->getTimestamp() - (int) $timestamp) > $tolerance) {
            throw new SignatureVerificationException('Timestamp outside the tolerance zone');
        }

        $messageId = $headers->get('twitch-eventsub-message-id');
        $signature = $headers->get('twitch-eventsub-message-signature');

        if (! $messageId || ! $signature) {
            throw new SignatureVerificationException('Required Twitch EventSub headers are missing');
        }

        $signatureParts = explode('=', $signature, 2);

        if (count($signatureParts) !== 2) {
            throw new SignatureVerificationException('Unable to parse signature from header');
        }

        [$algo, $givenSignature] = $signatureParts;

        $expectedSignature = hash_hmac($algo, $messageId.$rawTimestamp.$payload, $secret);

        if (! hash_equals($expectedSignature, $givenSignature)) {
            throw new SignatureVerificationException;
        }
    }

    public static function getTimestamp(?string $rawTimestamp): ?int
    {
        if ( ! $rawTimestamp) {
            return null;
        }

        if (preg_match('/\.([\d]{9,})Z$/', $rawTimestamp, $match)) {
            $length = strlen($match[1]) - 8;
            $rawTimestamp = substr_replace($rawTimestamp, '', ($length * -1) - 1, $length);
        }

        return Carbon::parse($rawTimestamp, 'UTC')->getTimestamp() ?: null;
    }
}
