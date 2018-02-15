<?php

namespace romanzipp\Twitch;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use romanzipp\Twitch\Helpers\Paginator;
use stdClass;

class Result
{
    /**
     * Query successfull
     * @var boolean
     */
    public $success = false;

    /**
     * Guzzle exception, if present
     * @var null|mixed
     */
    public $exception = null;

    /**
     * Query result data
     * @var array
     */
    public $data = [];

    /**
     * Total amount of result data
     * @var integer
     */
    public $total = 0;

    /**
     * Status Code
     * @var integer
     */
    public $status = 0;

    /**
     * Twitch response pagination cursor
     * @var null|\stdClass
     */
    public $pagination;

    /**
     * Internal paginator
     * @var null|Paginator
     */
    public $paginator;

    /**
     * Original Guzzle HTTP Response
     * @var Response
     */
    public $response;

    /**
     * Original Guzzle HTTP Request
     * @var Request
     */
    public $request;

    /**
     * Constructor
     * @param null|Response  $response  HTTP response
     * @param null|mixed     $exception Exception, if present
     * @param null|Paginator $paginator Paginator, if present
     * @param bool           $legacy    Is legacy v5 Request
     */
    public function __construct($response, $exception = null, $paginator = null, bool $legacy = false)
    {
        $this->response = $response;

        $this->success = $response instanceof Response;

        if ($exception) {
            $this->exception = $exception;
        }

        $this->status = $response->getStatusCode();

        $jsonResponse = $response === null ? [] : ($this->success ? @json_decode($response->getBody()) : null);

        if (!$legacy) {

            if ($jsonResponse !== null) {

                $this->setPropertiesByResponse($jsonResponse, [
                    'data',
                    'total',
                    'pagination',
                ]);

                $this->paginator = $paginator ?? Paginator::from($this);
            }

        } else {

            $this->data = $jsonResponse;

            if (property_exists($jsonResponse, '_total')) {

                $this->total = $jsonResponse->_total;
            }
        }
    }

    private function setPropertiesByResponse(stdClass $jsonResponse, array $properties)
    {
        foreach ($properties as $property) {
            if ($this->success && property_exists($jsonResponse, $property)) {
                $this->{$property} = $jsonResponse->{$property};
            }
        }
    }

    /**
     * Returns wether the query was successfull
     * @return bool Success state
     */
    public function success(): bool
    {
        return $this->success;
    }

    /**
     * Get the response data, also available as public attribute
     * @return mixed
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Returns the last HTTP or API error
     * @return string Error message
     */
    public function error(): string
    {
        if (!$this->exception || !$this->exception->hasResponse()) {
            return 'Twitch API Unavailable';
        }

        $exception = (string) $this->exception->getResponse()->getBody();
        $exception = @json_decode($exception);

        if (property_exists($exception, 'message')) {
            return $exception->message;
        }

        return strval($exception);
    }

    /**
     * Shifts the current result (Use for single user/video etc. query)
     * @return mixed Shifted data
     */
    public function shift()
    {
        if (!empty($this->data)) {

            $data = $this->data;

            return array_shift($data);
        }

        return null;
    }

    /**
     * Return the current count of items in dataset
     * @return int Count
     */
    public function count(): int
    {
        return count((array) $this->data);
    }

    /**
     * Set the Paginator to fetch the first set of results
     * @return null|Paginator
     */
    public function first()
    {
        return $this->paginator !== null ? $this->paginator->first() : null;
    }

    /**
     * Set the Paginator to fetch the next set of results
     * @return null|Paginator
     */
    public function next()
    {
        return $this->paginator !== null ? $this->paginator->next() : null;
    }

    /**
     * Set the Paginator to fetch the last set of results
     * @return null|Paginator
     */
    public function back()
    {
        return $this->paginator !== null ? $this->paginator->back() : null;
    }
}
