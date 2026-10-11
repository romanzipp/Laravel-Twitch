<?php

namespace romanzipp\Twitch\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use romanzipp\Twitch\Tests\TestCases\TestCase;
use romanzipp\Twitch\Twitch;

class EndpointRoutesTest extends TestCase
{
    #[DataProvider('endpoints')]
    public function testEndpointRoute(string $method, string $verb, string $path, array $parameters = [], ?array $body = null)
    {
        $history = [];

        $stack = HandlerStack::create(new MockHandler([new Response(200, [], '{"data":[]}')]));
        $stack->push(Middleware::history($history));

        $twitch = new Twitch();
        $twitch->setRequestClient(new Client(['handler' => $stack]));

        $parameters = array_fill_keys($parameters, '1');

        null === $body
            ? $twitch->withToken('token')->{$method}($parameters)
            : $twitch->withToken('token')->{$method}($parameters, array_fill_keys($body, '1'));

        $request = $history[0]['request'];

        self::assertSame($verb, $request->getMethod());
        self::assertSame($path, ltrim($request->getUri()->getPath(), '/'));
    }

    public static function endpoints(): array
    {
        return [
            ['getAdSchedule', 'GET', 'channels/ads', ['broadcaster_id']],
            ['snoozeNextAd', 'POST', 'channels/ads/schedule/snooze', ['broadcaster_id']],
            ['getCustomPowerUp', 'GET', 'bits/custom_power_ups', ['broadcaster_id']],
            ['getCharityCampaignDonations', 'GET', 'charity/donations', ['broadcaster_id']],
            ['getUserEmotes', 'GET', 'chat/emotes/user', ['user_id']],
            ['sendChatMessage', 'POST', 'chat/messages', [], ['broadcaster_id', 'sender_id', 'message']],
            ['getSharedChatSession', 'GET', 'shared_chat/session', ['broadcaster_id']],
            ['getPinnedChatMessage', 'GET', 'chat/pins', ['broadcaster_id', 'moderator_id']],
            ['pinChatMessage', 'PUT', 'chat/pins', ['broadcaster_id', 'moderator_id', 'message_id']],
            ['updatePinnedChatMessage', 'PATCH', 'chat/pins', ['broadcaster_id', 'moderator_id', 'message_id']],
            ['unpinChatMessage', 'DELETE', 'chat/pins', ['broadcaster_id', 'moderator_id', 'message_id']],
            ['createClipFromVod', 'POST', 'videos/clips', ['editor_id', 'broadcaster_id', 'vod_id', 'vod_offset', 'title']],
            ['getClipsDownload', 'GET', 'clips/downloads', ['editor_id', 'broadcaster_id', 'clip_id']],
            ['getConduits', 'GET', 'eventsub/conduits'],
            ['createConduits', 'POST', 'eventsub/conduits', [], ['shard_count']],
            ['updateConduits', 'PATCH', 'eventsub/conduits', [], ['id', 'shard_count']],
            ['deleteConduit', 'DELETE', 'eventsub/conduits', ['id']],
            ['getConduitShards', 'GET', 'eventsub/conduits/shards', ['conduit_id']],
            ['updateConduitShards', 'PATCH', 'eventsub/conduits/shards', [], ['conduit_id', 'shards']],
            ['getContentClassificationLabels', 'GET', 'content_classification_labels'],
            ['getChannelGuestStarSettings', 'GET', 'guest_star/channel_settings', ['broadcaster_id', 'moderator_id']],
            ['updateChannelGuestStarSettings', 'PUT', 'guest_star/channel_settings', ['broadcaster_id'], []],
            ['getGuestStarSession', 'GET', 'guest_star/session', ['broadcaster_id', 'moderator_id']],
            ['createGuestStarSession', 'POST', 'guest_star/session', ['broadcaster_id']],
            ['endGuestStarSession', 'DELETE', 'guest_star/session', ['broadcaster_id', 'session_id']],
            ['getGuestStarInvites', 'GET', 'guest_star/invites', ['broadcaster_id', 'moderator_id', 'session_id']],
            ['sendGuestStarInvite', 'POST', 'guest_star/invites', ['broadcaster_id', 'moderator_id', 'session_id', 'guest_id']],
            ['deleteGuestStarInvite', 'DELETE', 'guest_star/invites', ['broadcaster_id', 'moderator_id', 'session_id', 'guest_id']],
            ['assignGuestStarSlot', 'POST', 'guest_star/slot', ['broadcaster_id', 'moderator_id', 'session_id', 'guest_id', 'slot_id']],
            ['updateGuestStarSlot', 'PATCH', 'guest_star/slot', ['broadcaster_id', 'moderator_id', 'session_id', 'source_slot_id']],
            ['deleteGuestStarSlot', 'DELETE', 'guest_star/slot', ['broadcaster_id', 'moderator_id', 'session_id', 'guest_id', 'slot_id']],
            ['updateGuestStarSlotSettings', 'PATCH', 'guest_star/slot_settings', ['broadcaster_id', 'moderator_id', 'session_id', 'slot_id']],
            ['getHypeTrainStatus', 'GET', 'hypetrain/status', ['broadcaster_id']],
            ['getModeratedChannels', 'GET', 'moderation/channels', ['user_id']],
            ['getUnbanRequests', 'GET', 'moderation/unban_requests', ['broadcaster_id', 'moderator_id', 'status']],
            ['resolveUnbanRequests', 'PATCH', 'moderation/unban_requests', ['broadcaster_id', 'moderator_id', 'unban_request_id', 'status']],
            ['warnChatUser', 'POST', 'moderation/warnings', ['broadcaster_id', 'moderator_id'], ['data']],
            ['addSuspiciousStatusToChatUser', 'POST', 'moderation/suspicious_users', ['broadcaster_id', 'moderator_id'], ['user_id', 'status']],
            ['removeSuspiciousStatusFromChatUser', 'DELETE', 'moderation/suspicious_users', ['broadcaster_id', 'moderator_id', 'user_id']],
            ['getAuthorizationByUser', 'GET', 'authorization/users', ['user_id']],
        ];
    }
}
