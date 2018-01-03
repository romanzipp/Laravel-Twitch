<?php

namespace romanzipp\Twitch\Traits;

use romanzipp\Twitch\Result;

trait VideosTrait
{
    public function getVideo(int $id): Result
    {
        $options = [
            'id' => $id
        ];

        return $this->sendRequest('GET', 'videos', false, $options);
    }

    public function getVideos(array $ids): Result
    {
        $options = [
            'id' => $ids
        ];

        return $this->sendRequest('GET', 'videos', false, $options);
    }

    public function getVideosByUser(int $user): Result
    {
        $options = [
            'user_id' => $user
        ];

        return $this->sendRequest('GET', 'videos', false, $options);
    }

    public function getVideosByGame(int $game): Result
    {
        $options = [
            'game_id' => $game
        ];

        return $this->sendRequest('GET', 'videos', false, $options);
    }
}
