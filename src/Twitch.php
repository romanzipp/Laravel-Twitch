<?php

namespace romanzipp\Twitch;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException;
use romanzipp\Twitch\Exceptions\RequestRequiresClientIdException;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Traits\BitsTrait;
use romanzipp\Twitch\Traits\ClipsTrait;
use romanzipp\Twitch\Traits\EntitlementsTrait;
use romanzipp\Twitch\Traits\ExtensionsTrait;
use romanzipp\Twitch\Traits\FollowsTrait;
use romanzipp\Twitch\Traits\GamesTrait;
use romanzipp\Twitch\Traits\Legacy\OAuthTrait as LegacyOAuthTrait;
use romanzipp\Twitch\Traits\Legacy\RootTrait as LegacyRootTrait;
use romanzipp\Twitch\Traits\StreamsMetadataTrait;
use romanzipp\Twitch\Traits\StreamsTrait;
use romanzipp\Twitch\Traits\UsersTrait;
use romanzipp\Twitch\Traits\VideosTrait;
use romanzipp\Twitch\Traits\WebhooksTrait;

class Twitch
{
    use BitsTrait;
    use ClipsTrait;
    use EntitlementsTrait;
    use ExtensionsTrait;
    use FollowsTrait;
    use GamesTrait;
    use StreamsTrait;
    use StreamsMetadataTrait;
    use UsersTrait;
    use VideosTrait;

    use WebhooksTrait;

    use LegacyOAuthTrait;
    use LegacyRootTrait;

    const BASE_URI = 'https://api.twitch.tv/helix/';

    /**
     * Guzzle is used to make http requests
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Paginator object
     * @var Paginator
     */
    protected $paginator;

    /**
     * Twitch token
     * @var string
     */
    protected $token = null;

    /**
     * Twitch client id
     * @var string
     */
    protected $clientId = null;

    /**
     * Twitch client secret
     * @var string
     */
    protected $clientSecret = null;

    /**
     * Is legacy Request
     * @var null|bool
     */
    protected $legacy = null;

    /**
     * Construction
     * @param string $token    Twitch OAuth Token
     * @param string $clientId Twitch client id
     */
    public function __construct(string $token = null, string $clientId = null)
    {
        if ($token !== null) {
            $this->setToken($token);
        }

        if ($clientId !== null) {
            $this->setClientId($clientId);
        } elseif (config('twitch-api.client_id')) {
            $this->setClientId(config('twitch-api.client_id'));
            $this->setClientSecret(config('twitch-api.client_secret'));
        }

        $this->client = new Client([
            'base_uri' => self::BASE_URI,
        ]);
    }

    /**
     * Set clientId
     * @param string $clientId Twitch client id
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * Get clientId
     * @param  string $clientId clientId optional
     * @return string           clientId
     */
    public function getClientId()
    {
        if (!$this->clientId) {
            throw new RequestRequiresClientIdException();
        }

        return $this->clientId;
    }

    /**
     * Set clientSecret
     * @param string $clientSecret Twitch client id
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Get clientSecret
     * @param  string $clientSecret clientSecret optional
     * @return string               clientSecret
     */
    public function getClientSecret()
    {
        if (!$this->clientSecret) {
            throw new RequestRequiresClientIdException();
        }

        return $this->clientSecret;
    }

    /**
     * Set Twitch OAuth Token
     * @param string $token OAuth token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * Set Twitch OAuth Token for one request
     * @param  string $token OAuth token
     * @return self
     */
    public function withToken(string $token)
    {
        $this->setToken($token);

        return $this;
    }

    /**
     * Get Twitch token
     * @return string Twitch token
     */
    public function getToken()
    {
        if (!$this->token) {
            throw new RequestRequiresAuthenticationException();
        }

        return $this->token;
    }

    /**
     * Set legacy mode for one Request
     * @return self
     */
    public function withLegacy()
    {
        $this->legacy = true;
  
        return $this;
    }

    public function get(string $path = '', array $parameters = [], Paginator $paginator = null)
    {
        return $this->query('GET', $path, $parameters, $paginator);
    }

    public function post(string $path = '', array $parameters = [], Paginator $paginator = null)
    {
        return $this->query('POST', $path, $parameters, $paginator);
    }

    public function put(string $path = '', array $parameters = [], Paginator $paginator = null)
    {
        return $this->query('PUT', $path, $parameters, $paginator);
    }

    public function json(string $method, string $path = '', array $body = null)
    {
        if ($body) {
            $body = json_encode(['data' => $body]);
        }

        return $this->query($method, $path, [], null, $body);
    }

    /**
     * Execute query
     * @param  string $method     HTTP method
     * @param  string $path       Query path
     * @param  array  $parameters Query parameters
     * @param  mixed  $token      Token String or true/false to obtain by setToken method
     * @return Result             Result object
     */
    public function query(string $method = 'GET', string $path = '', array $parameters = [], Paginator $paginator = null, $jsonBody = null): Result
    {
        if ($paginator !== null) {
            $parameters[$paginator->action] = $paginator->cursor();
        }

        $uri = $this->generateUrl($path, $parameters);

        $headers = $this->generateHeaders($jsonBody ? true : false);

        $result = $this->executeQuery($method, $uri, $headers, $paginator, $jsonBody);

        return $result;
    }

    /**
     * Execute query
     * @param  string $method   HTTP method
     * @param  string $uri      Query path
     * @param  array  $headers  Query headers
     * @param  mixed  $jsonBody JSON Body
     * @return Result
     */
    private function executeQuery(string $method, string $uri, array $headers, Paginator $paginator = null, $jsonBody = null): Result
    {
        try {
            $request = new Request($method, $uri, $headers, $jsonBody);

            $response = $this->client->send($request);

            $result = new Result($response, null, $paginator, $this->legacy ? true : false);
        } catch (RequestException $exception) {
            $result = new Result($exception->getResponse(), $exception, $paginator);
        } catch (ClientException $exception) {
            $result = new Result($exception->getResponse(), $exception, $paginator);
        }

        $result->request = $request;
        $result->twitch = $this;

        return $result;
    }

    /**
     * Generate URL for API
     * @param  string      $url        Query uri
     * @param  null|string $token      Auth token, if required
     * @param  array       $parameters Query parameters
     * @return string                  Full query url
     */
    private function generateUrl(string $url, array $parameters): string
    {
        foreach ($parameters as $optionKey => $option) {
            $data = !is_array($option) ? [$option] : $option;

            foreach ($data as $key => $value) {
                $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . $optionKey . '=' . $value;
            }
        }

        return $url;
    }

    /**
     * Generate headers
     * @param  bool  $json Body is JSON
     * @return array
     */
    public function generateHeaders(bool $json = false): array
    {
        $headers = [
            'Client-ID' => $this->getClientId(),
        ];

        if ($this->token) {
            $headers['Authorization'] = ($this->legacy ? 'OAuth ' : 'Bearer ') . $this->token;
        }

        if ($json) {
            $headers['Content-Type'] = 'application/json';
        }

        return $headers;
    }
}
