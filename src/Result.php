<?php

namespace romanzipp\Twitch;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use romanzipp\Twitch\Objects\Paginator;
use stdClass;

class Result
{
    /**
     * Guzzle exception, if present.
     *
     * @var \Psr\Http\Client\RequestExceptionInterface|null
     */
    private $exception;

    /**
     * Query result data.
     *
     * @var array|\stdClass
     */
    private $data = [];

    /**
     * Message field, if present.
     *
     * @var string|null
     */
    private $message;

    /**
     * Total amount of result data.
     *
     * @var int
     */
    private $total = 0;

    /**
     * Status Code.
     *
     * @var int
     */
    private $status;

    /**
     * Twitch response pagination cursor.
     *
     * @var \stdClass|null
     */
    private $pagination;

    /**
     * Internal paginator.
     *
     * @var Paginator|null
     */
    private $paginator;

    /**
     * Original Guzzle HTTP Response.
     *
     * @var \Psr\Http\Message\ResponseInterface
     */
    public $response;

    public function __construct(ResponseInterface $response, RequestExceptionInterface $exception = null)
    {
        $this->response = $response;
        $this->status = $response->getStatusCode();

        $this->exception = $exception;

        $this->processPayload($response);

        $this->paginator = Paginator::from($this);
    }

    public function success(): bool
    {
        return null === $this->exception;
    }

    public function data()
    {
        return $this->data;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getPagination(): ?stdClass
    {
        return $this->pagination;
    }

    public function getPaginator(): ?Paginator
    {
        return $this->paginator;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getException(): ?RequestExceptionInterface
    {
        return $this->exception;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
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
     * Returns the last HTTP or API error.
     *
     * @return string Error message
     */
    public function getErrorMessage(): string
    {
        if (null === $this->exception || null === $this->exception->getResponse()) {
            return 'Twitch API Unavailable';
        }

        if (null === $this->message || ! is_string($this->message)) {
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
     * Set the Paginator to fetch the next set of results.
     *
     * @return \romanzipp\Twitch\Objects\Paginator|null
     */
    public function next(): ?Paginator
    {
        if (null === $this->paginator) {
            return null;
        }

        return $this->paginator->next();
    }

    /**
     * Set the Paginator to fetch the last set of results.
     *
     * @return \romanzipp\Twitch\Objects\Paginator|null
     */
    public function back(): ?Paginator
    {
        if (null === $this->paginator) {
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
        if (0 === $this->count()) {
            return false;
        }

        if (null === $this->paginator) {
            return false;
        }

        return null !== $this->paginator->cursor();
    }

    /**
     * Get rate limit information.
     *
     * @param string|null $key Get an index value. Available: limit, remaining, reset
     *
     * @return string|array|null
     */
    public function getRateLimit(string $key = null)
    {
        if ( ! $this->response || ! $this->response->hasHeader('Ratelimit-Remaining')) {
            return null;
        }

        $rateLimit = [
            'limit' => (int) $this->response->getHeaderLine('Ratelimit-Limit'),
            'remaining' => (int) $this->response->getHeaderLine('Ratelimit-Remaining'),
            'reset' => (int) $this->response->getHeaderLine('Ratelimit-Reset'),
        ];

        if (null === $key) {
            return $rateLimit;
        }

        return $rateLimit[$key] ?? null;
    }

    public function isOAuthError(): bool
    {
        if (null === $this->exception) {
            return false;
        }

        return 401 === $this->getStatus() && in_array($this->getErrorMessage(), [
            'Invalid OAuth token',
            'OAuth token is missing',
        ]);
    }

    /**
     * Insert users in data response.
     *
     * @param \romanzipp\Twitch\Twitch $twitch
     * @param string $identifierAttribute Attribute to identify the users
     * @param string $insertTo Data index to insert user data
     *
     * @return self
     */
    public function insertUsers(Twitch $twitch, string $identifierAttribute = 'user_id', string $insertTo = 'user'): self
    {
        $data = $this->data;

        $userIds = collect($data)->map(function ($item) use ($identifierAttribute) {
            return $item->{$identifierAttribute};
        })->toArray();

        if (0 === count($userIds)) {
            return $this;
        }

        $users = collect($twitch->getUsers(['id' => $userIds])->data);

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

        if (null === $payload) {
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
