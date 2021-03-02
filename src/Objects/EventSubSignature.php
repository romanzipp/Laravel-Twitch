<?php

namespace romanzipp\Twitch\Objects;

use DateTime;
use romanzipp\Twitch\Exceptions\SignatureVerificationException;
use Symfony\Component\HttpFoundation\HeaderBag;

class EventSubSignature
{
    /**
     * Represents the timestamp format of twitch's eventsub api.
     */
    const TIMESTAMP_PATTERN = '/^(?<pre>[\d\-:.T]+)\.(?<nano>\d{6,9})Z$/';

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
        $expectedSignature = hash_hmac('sha256', $messageId . $rawTimestamp . $payload, $secret);

        if ($headers->get('twitch-eventsub-message-signature') !== $expectedSignature) {
            throw new SignatureVerificationException();
        }
    }

    private static function getTimestamp(?string $rawTimestamp): ?int
    {
        if ( ! $rawTimestamp) {
            return null;
        }

        if ( ! preg_match(self::TIMESTAMP_PATTERN, $rawTimestamp, $matches)) {
            return null;
        }

        $dateTime = DateTime::createFromFormat(
            'Y-m-d\TH:i:s.u\Z',
            sprintf('%s.%dZ', $matches['pre'], substr($matches['nano'], 0, 6))
        );

        return $dateTime->getTimestamp();
    }
}
