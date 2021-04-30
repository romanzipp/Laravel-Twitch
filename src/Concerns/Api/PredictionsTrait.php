<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait PredictionsTrait
{
    use AbstractOperationsTrait;
    use AbstractValidationTrait;

    public function getPredictions(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('predictions', $parameters, $paginator);
    }

    public function createPredictions(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, [
            'broadcaster_id',
            'title',
            'outcomes',
            'prediction_window',
        ]);

        return $this->post('predictions', $parameters, null, $body);
    }

    public function updatePrediction(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, [
            'broadcaster_id',
            'id',
            'status',
        ]);

        return $this->patch('predictions', $parameters, null, $body);
    }
}
