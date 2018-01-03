<?php

namespace romanzipp\Twitch;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException;
use romanzipp\Twitch\Exceptions\RequestRequiresClientIdException;
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

    public function mergePaginator(&$options, Paginator $paginator = null)
    {
        if ($paginator) {

            if ($paginator->after) {
                $options['after'] = $paginator->after;
            }

            if ($paginator->before) {
                $options['before'] = $paginator->before;
            }

            if ($paginator->first) {
                $options['first'] = $paginator->first;
            }
        }
    }
    public function parseAttribute($attribute, $integerIdentifier = 'id', $stringIdentifier = 'name')
    {
        if (is_integer($attribute)) {
            return [$integerIdentifier => $attribute];
        }

        return [$stringIdentifier => $attribute];
    }

    public function parseAttributes(array $attributes, $integerIdentifier = 'id', $stringIdentifier = 'name')
    {
        $return = [];

        foreach ($attributes as $key => $attribute) {

            if (is_integer($attribute)) {

                if (!array_key_exists($stringIdentifier, $return)) {
                    $return[$stringIdentifier] = [];
                }

                $return[$stringIdentifier][] = $attribute;

            } else {

                if (!array_key_exists($integerIdentifier, $return)) {
                    $return[$integerIdentifier] = [];
                }

                $return[$integerIdentifier][] = $attribute;
            }
        }

        return $return;
    }

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

    public function get($path = '', $parameters = [], $token = false)
    {
        return $this->query('GET', $path, $parameters, $token);
    }

    public function post($path = '', $parameters = [], $token = false)
    {
        return $this->query('POST', $path, $parameters, $token);
    }

    public function put($path = '', $parameters = [], $token = false)
    {
        return $this->query('PUT', $path, $parameters, $token);
    }

    public function query($method = 'GET', $path = '', $parameters = [], $token = false)
    {
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

            return new Result($response);

        } catch (RequestException $e) {

            return new Result(null, $e);
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
