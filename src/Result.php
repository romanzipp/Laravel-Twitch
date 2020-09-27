<?php

namespace romanzipp\Twitch;

use Exception;
use Psr\Http\Message\ResponseInterface;
use romanzipp\Twitch\Helpers\Paginator;

class Result
{
    /**
     * Query successful.
     *
     * @var boolean
     */
    public $success = false;

    /**
     * Guzzle exception, if present.
     *
     * @var mixed|null
     */
    public $exception = null;

    /**
     * Query result data.
     *
     * @var array|\stdClass
     */
    public $data = [];

    /**
     * Message field, if present.
     *
     * @var string|null
     */
    public $message = null;

    /**
     * Total amount of result data.
     *
     * @var integer
     */
    public $total = 0;

    /**
     * Status Code.
     *
     * @var integer
     */
    public $status = 0;

    /**
     * Twitch response pagination cursor.
     *
     * @var \stdClass|null
     */
    public $pagination;

    /**
     * Internal paginator.
     *
     * @var Paginator|null
     */
    public $paginator;

    /**
     * Original Guzzle HTTP Response.
     *
     * @var \Psr\Http\Message\ResponseInterface
     */
    public $response;

    /**
     * Original Twitch instance.
     *
     * @var \romanzipp\Twitch\Twitch
     */
    public $twitch;

    /**
     * Constructor,
     *
     * @param \Psr\Http\Message\ResponseInterface $response HTTP response
     * @param \Exception|mixed $exception Exception, if present
     */
    public function __construct(ResponseInterface $response, Exception $exception = null)
    {
        $this->response = $response;
        $this->status = $response->getStatusCode();

        $this->success = $exception === null;
        $this->exception = $exception;

        $this->processPayload($response);

        $this->paginator = Paginator::from($this);
    }

    /**
     * Returns whether the query was successful.
     *
     * @return bool Success state
     */
    public function success(): bool
    {
        return $this->success;
    }

    /**
     * Get the response data, also available as public attribute.
     *
     * @return mixed
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Get the response status code.
     *
     * @return int
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * Returns the last HTTP or API error.
     *
     * @return string Error message
     */
    public function error(): string
    {
        if ($this->exception === null || ! $this->exception->hasResponse()) {
            return 'Twitch API Unavailable';
        }

        if ($this->message === null || ! is_string($this->message)) {
            return $this->exception->getMessage();
        }

        return $this->message;
    }

    /**
     * Shifts the current result (Use for single user/video etc. query).
     *
     * @return mixed Shifted data
     */
    public function shift()
    {
        if ( ! is_array($this->data)) {
            return null;
        }

        if ( ! empty($data = $this->data)) {
            return array_shift($data);
        }

        return null;
    }

    /**
     * Return the current count of items in dataset.
     *
     * @return int Count
     */
    public function count(): int
    {
        if ( ! is_array($this->data)) {
            return 0;
        }

        return count((array) $this->data);
    }

    /**
     * Set the Paginator to fetch the first set of results.
     *
     * @return \romanzipp\Twitch\Helpers\Paginator|null
     * @deprecated
     */
    public function first(): ?Paginator
    {
        if ($this->paginator === null) {
            return null;
        }

        return $this->paginator->first();
    }

    /**
     * Set the Paginator to fetch the next set of results.
     *
     * @return \romanzipp\Twitch\Helpers\Paginator|null
     */
    public function next(): ?Paginator
    {
        if ($this->paginator === null) {
            return null;
        }

        return $this->paginator->next();
    }

    /**
     * Set the Paginator to fetch the last set of results.
     *
     * @return \romanzipp\Twitch\Helpers\Paginator|null
     */
    public function back(): ?Paginator
    {
        if ($this->paginator === null) {
            return null;
        }

        return $this->paginator->back();
    }

    /**
     * Check if the response contains a cursor to the next set of results.
     *
     * @return bool
     */
    public function hasMoreResults(): bool
    {
        if ($this->count() === 0) {
            return false;
        }

        if ($this->paginator === null) {
            return false;
        }

        return $this->paginator->cursor() !== null;
    }

    /**
     * Get rate limit information.
     *
     * @param string|null $key Get an index value. Available: limit, remaining, reset
     * @return string|array|null
     */
    public function rateLimit(string $key = null)
    {
        if ( ! $this->response || ! $this->response->hasHeader('Ratelimit-Remaining')) {
            return null;
        }

        $rateLimit = [
            'limit' => (int) $this->response->getHeaderLine('Ratelimit-Limit'),
            'remaining' => (int) $this->response->getHeaderLine('Ratelimit-Remaining'),
            'reset' => (int) $this->response->getHeaderLine('Ratelimit-Reset'),
        ];

        if ($key === null) {
            return $rateLimit;
        }

        return $rateLimit[$key] ?? null;
    }

    /**
     * Insert users in data response.
     *
     * @param string $identifierAttribute Attribute to identify the users
     * @param string $insertTo Data index to insert user data
     * @return self
     */
    public function insertUsers(string $identifierAttribute = 'user_id', string $insertTo = 'user'): self
    {
        $data = $this->data;

        $userIds = collect($data)->map(function ($item) use ($identifierAttribute) {
            return $item->{$identifierAttribute};
        })->toArray();

        if (count($userIds) === 0) {
            return $this;
        }

        $users = collect($this->twitch->getUsers(['id' => $userIds])->data);

        $dataWithUsers = collect($data)
            ->map(static function ($item) use ($users, $identifierAttribute, $insertTo) {

                $item->$insertTo = $users->where('id', $item->{$identifierAttribute})->first();

                return $item;
            });

        $this->data = $dataWithUsers->toArray();

        return $this;
    }

    /**
     * Map response payload to instance attributes.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    protected function processPayload(ResponseInterface $response): void
    {
        $payload = @json_decode(
            $response->getBody()
        );

        if ($payload === null) {
            return;
        }

        $this->message = $payload->message ?? null;
        $this->total = $payload->total ?? 0;
        $this->pagination = $payload->pagination ?? null;

        if ( ! property_exists($payload, 'data')) {
            $this->data = $payload;
            return;
        }

        $this->data = $payload->data ?? [];
    }
}
