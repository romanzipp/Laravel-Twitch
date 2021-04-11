<?php

namespace romanzipp\Twitch\Objects;

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
    public static function verifyHeader(string $payload, HeaderBag $headers, string $secret, int $tolerance = 60): void
    {
        $rawTimestamp = $headers->get('twitch-eventsub-message-timestamp');
        $timestamp = self::getTimestamp($rawTimestamp);

        if ( ! is_numeric($timestamp)) {
            throw new SignatureVerificationException('Unable to extract timestamp and signatures from header');
        }

        if ($tolerance > 0 && (time() - $timestamp) > $tolerance) {
            throw new SignatureVerificationException('Timestamp outside the tolerance zone');
        }

        $messageId = $headers->get('twitch-eventsub-message-id');
        [$algo, $givenSignature] = explode('=', $headers->get('twitch-eventsub-message-signature'));
        $expectedSignature = hash_hmac($algo, $messageId . $rawTimestamp . $payload, $secret);

        if ($givenSignature !== $expectedSignature) {
            throw new SignatureVerificationException();
        }
    }

    private static function getTimestamp(?string $rawTimestamp): ?int
    {
        if ( ! $rawTimestamp) {
            return null;
        }

        if (preg_match('/\.([\d]{9,})Z$/', $rawTimestamp, $match)) {
            $length = strlen($match[1]) - 8;
            $rawTimestamp = substr_replace($rawTimestamp, '', ($length * -1) - 1, $length);
        }

        $timestamp = strtotime($rawTimestamp);

        return $timestamp ?: null;
    }
}
