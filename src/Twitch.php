<?php

namespace romanzipp\Twitch;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException;
use romanzipp\Twitch\Exceptions\RequestRequiresClientIdException;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Traits\ClipsTrait;
use romanzipp\Twitch\Traits\FollowsTrait;
use romanzipp\Twitch\Traits\GamesTrait;
use romanzipp\Twitch\Traits\StreamsTrait;
use romanzipp\Twitch\Traits\UsersTrait;
use romanzipp\Twitch\Traits\VideosTrait;

class Twitch
{
    use ClipsTrait;
    use FollowsTrait;
    use GamesTrait;
    use StreamsTrait;
    use UsersTrait;
    use VideosTrait;    

    const BASE_URI = 'https://api.twitch.tv/helix/';

    /**
     * Twitch token.
     *
     * @var token
     */
    protected $token;

    /**
     * Twitch client id.
     *
     * @var clientId
     */

    protected $clientId;

    /**
     * Guzzle is used to make http requests.
     *
     * @var GuzzleClient
     */
    protected $client;

    /**
     * Paginator object
     * 
     * @var Paginator
     */
    protected $paginator;

    /**
     * Construction.
     *
     * @param string $token    Twitch OAuth Token
     * @param string $clientId Twitch client id
     */
    public function __construct($token = null, $clientId = null)
    {
        if ($token) {
            $this->setToken($token);
        }

        if ($clientId) {

            $this->setClientId($clientId);

        } elseif (config('twitch-api.client_id')) {

            $this->setClientId(config('twitch-api.client_id'));
        }

        $this->client = new Client([
            'base_uri' => self::BASE_URI,
        ]);
    }

    /**
     * Set clientId.
     *
     * @param string $clientId Twitch client id
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * Get clientId.
     *
     * @param string clientId optional
     *
     * @return string clientId
     */
    public function getClientId($clientId = null)
    {
        if ($clientId) {
            return $clientId;
        }

        if (!$this->clientId) {
            throw new RequestRequiresClientIdException();
        }

        return $this->clientId;
    }

    /**
     * Set Twitch OAuth Token.
     *
     * @param string $token OAuth token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Get Twitch token.
     *
     * @param string $token Twitch token
     *
     * @return string Twitch token
     */
    public function getToken($token = null)
    {
        if ($token) {
            return $token;
        }

        if (!$this->token) {
            throw new RequestRequiresAuthenticationException();
        }

        return $this->token;
    }

    public function get($path = '', $parameters = [], $token = null, Paginator $paginator = null)
    {
        return $this->query('GET', $path, $parameters, $token, $paginator);
    }

    public function post($path = '', $parameters = [], $token = null, Paginator $paginator = null)
    {
        return $this->query('POST', $path, $parameters, $token, $paginator);
    }

    public function put($path = '', $parameters = [], $token = null, Paginator $paginator = null)
    {
        return $this->query('PUT', $path, $parameters, $token, $paginator);
    }

    /**
     * Execute query
     * @param  string $method     HTTP method
     * @param  string $path       Query path
     * @param  array  $parameters Query parameters
     * @param  [type] $token      [description]
     * @return Result             Result object
     */
    public function query(string $method = 'GET', string $path = '', array $parameters = [], $token = null, Paginator $paginator = null): Result
    {
        if ($paginator) {
            $parameters[$paginator->action] = $paginator->cursor();
        }

        $uri = $this->generateUrl($path, $token, $parameters);

        $headers = [
            'Client-ID' => $this->getClientId(),
        ];

        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $this->getToken($token);
        }

        try {
            $request = new Request($method, $uri, $headers);

            $response = $this->client->send($request);

            return new Result($response, null, $paginator);

        } catch (RequestException $e) {

            return new Result(null, $e, $paginator);
        }
    }

    public function generateUrl($url, $token, $parameters)
    {
        if ($token) {
            $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'oauth_token=' . $this->getToken($token);
        }

        foreach ($parameters as $optionKey => $option) {

            $data = !is_array($option) ? [$option] : $option;

            foreach ($data as $key => $value) {
                $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . $optionKey . '=' . $value;
            }
        }

        return $url;
    }
}
