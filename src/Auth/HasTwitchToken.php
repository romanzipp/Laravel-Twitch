<?php

namespace romanzipp\Twitch\Auth;

use stdClass;

trait HasTwitchToken
{
    protected ?stdClass $twitchToken;

    public function getTwitchToken(): ?stdClass
    {
        return $this->twitchToken;
    }

    public function withTwitchToken(?stdClass $decoded): self
    {
        $this->twitchToken = $decoded;

        return $this;
    }

    public static function createFromTwitchToken($decoded, array $attributes = []): ?self
    {
        return null;
    }
}
