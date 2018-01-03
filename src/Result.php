<?php

namespace romanzipp\Twitch;

use GuzzleHttp\Psr7\Response;
use romanzipp\Twitch\Helpers\Paginator;

class Result
{
    private $success = false;

    private $exception = null;

    public $data = [];

    public $total = 0;

    public $pagination;

    public $paginator;

    public function __construct($response, $exception = false, $paginator = null)
    {
        $this->success = $response instanceof Response;

        if ($exception) {
            $this->exception = $exception;
        }

        $jsonResponse = $this->success ? @json_decode($response->getBody()) : null;

        if ($this->success && property_exists($jsonResponse, 'data')) {
            $this->data = $jsonResponse->data;
        }

        if ($this->success && property_exists($jsonResponse, 'total')) {
            $this->total = $jsonResponse->total;
        } else {
            $this->total = count((array) $this->data);
        }

        if ($this->success && property_exists($jsonResponse, 'pagination')) {
            $this->pagination = $jsonResponse->pagination;
        }

        $this->paginator = $paginator ?? Paginator::from($this);
    }

    public function success(): bool
    {
        return $this->success;
    }

    public function error(): string
    {
        if (!$this->exception || !$this->exception->hasResponse()) {
            return 'Twitch API Unavailable';
        }

        $exception = (string) $e->getResponse()->getBody();
        $exception = @json_decode($exception);

        if (property_exists($exception, 'message')) {
            $exception->message;
        }

        return strval($exception);
    }

    public function shift()
    {
        if ($this->data) {

            $data = $this->data;

            return array_shift($data);
        }

        return null;
    }

    public function first()
    {
        return $this->paginator->first();
    }

    public function next()
    {
        return $this->paginator->next();
    }

    public function back()
    {
        return $this->paginator->back();
    }
}
