<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Result;

trait ContentClassificationLabelsTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Gets information about Twitch content classification labels.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-content-classification-labels
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getContentClassificationLabels(array $parameters = []): Result
    {
        return $this->get('content_classification_labels', $parameters);
    }
}
