<?php

namespace romanzipp\Twitch\Tests\Api;

use romanzipp\Twitch\Enums\PredictionStatus;
use romanzipp\Twitch\Tests\TestCases\ApiTestCase;

class PredictionsTest extends ApiTestCase
{
    public function testGetPredictions()
    {
        $this->skipIfTokenMissing();
        $this->skipIfBroadcasterIdMissing();

        $this->registerResult(
            $result = $this->twitch()->withToken($this->getToken())->getPredictions([
                'broadcaster_id' => $this->getBroadcasterId(),
            ])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
    }

    public function testCreatePredictions()
    {
        $this->skipIfTokenMissing();
        $this->skipIfBroadcasterIdMissing();

        $this->registerResult(
            $result = $this->twitch()->withToken($this->getToken())->createPrediction([], [
                'broadcaster_id' => $this->getBroadcasterId(),
                'title' => 'Hello World!',
                'outcomes' => [
                    [
                        'title' => 'A eSports',
                    ],
                    [
                        'title' => 'B eSports',
                    ],
                ],
                'prediction_window' => 30,
            ])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
    }

    /**
     * @depends testCreatePredictions
     */
    public function testEndPrediction()
    {
        $this->skipIfTokenMissing();
        $this->skipIfBroadcasterIdMissing();

        $prediction = $this->twitch()->withToken($this->getToken())->getPredictions([
            'broadcaster_id' => $this->getBroadcasterId(),
        ])->shift();

        if ( ! $prediction) {
            self::markTestSkipped('No Prediction provided');
        }

        $this->registerResult(
            $result = $this->twitch()->endPrediction([], [
                'broadcaster_id' => '106415581',
                'id' => $prediction->id,
                'status' => PredictionStatus::CANCELED,
            ])
        );

        self::assertTrue($result->success(), $result->getErrorMessage());
        self::assertNotEmpty($result->data());
    }
}
