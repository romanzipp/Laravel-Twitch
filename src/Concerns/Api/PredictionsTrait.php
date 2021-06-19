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

    /**
     * Get information about all Channel Points Predictions or specific Channel Points Predictions for a Twitch channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-predictions
     *
     * @param array $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     */
    public function getPredictions(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);

        return $this->get('predictions', $parameters, $paginator);
    }

    /**
     * Create a Channel Points Prediction for a specific Twitch channel.
     *
     * @see https://dev.twitch.tv/docs/api/reference#create-prediction
     *
     * @param array $parameters
     * @param array $body
     *
     * @return Result
     */
    public function createPrediction(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, [
            'broadcaster_id',
            'title',
            'outcomes',
            'prediction_window',
        ]);

        return $this->post('predictions', $parameters, null, $body);
    }

    /**
     * Lock, resolve, or cancel a Channel Points Prediction.
     * Active Predictions can be updated to be “locked,” “resolved,” or “canceled.”
     * Locked Predictions can be updated to be “resolved” or “canceled.”.
     *
     * @see https://dev.twitch.tv/docs/api/reference#end-prediction
     *
     * @param array $parameters
     * @param array $body
     *
     * @return Result
     */
    public function endPrediction(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, [
            'broadcaster_id',
            'id',
            'status',
        ]);

        return $this->patch('predictions', $parameters, null, $body);
    }
}
