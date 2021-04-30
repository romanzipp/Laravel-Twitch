<?php

namespace romanzipp\Twitch\Enums;

class PollStatus
{
    // Poll is currently in progress.
    public const ACTIVE = 'ACTIVE';

    // Poll has reached its ended_at time.
    public const COMPLETED = 'COMPLETED';

    // Poll has been manually terminated before its ended_at time.
    public const TERMINATED = 'TERMINATED';

    // Poll is no longer visible on the channel.
    public const ARCHIVED = 'ARCHIVED';

    // Poll is no longer visible to any user.
    public const MODERATED = 'MODERATED';

    // Something went wrong determining the state.
    public const INVALID = 'INVALID';
}
