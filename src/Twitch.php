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
use romanzipp\Twitch\Traits\ExtensionsTrait;
use romanzipp\Twitch\Traits\FollowsTrait;
use romanzipp\Twitch\Traits\GamesTrait;
use romanzipp\Twitch\Traits\StreamsMetadataTrait;
use romanzipp\Twitch\Traits\StreamsTrait;
use romanzipp\Twitch\Traits\SubscriptionsTrait;
use romanzipp\Twitch\Traits\UsersTrait;
use romanzipp\Twitch\Traits\VideosTrait;
use romanzipp\Twitch\Traits\WebhooksTrait;

class Twitch
{
    use BitsTrait;
    use ClipsTrait;
    use ExtensionsTrait;
    use FollowsTrait;
    use GamesTrait;
    use StreamsTrait;
    use StreamsMetadataTrait;
    use UsersTrait;
    use VideosTrait;
    use SubscriptionsTrait;

    use WebhooksTrait;

    const BASE_URI = 'https://api.twitch.tv/helix/';

    /**
     * Guzzle is used to make http requests.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Paginator object.
     *
     * @var Paginator
     */
    protected $paginator;

    /**
     * Twitch OAuth token.
     *
     * @var string
     */
    protected $token = null;

    /**
     * Twitch client id.
     *
     * @var string
     */
    protected $clientId = null;

    /**
     * Twitch client secret.
     *
     * @var string
     */
    protected $clientSecret = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        if ($clientId = config('twitch-api.client_id')) {
            $this->setClientId($clientId);
        }

        if ($clientSecret = config('twitch-api.client_secret')) {
            $this->setClientSecret($clientSecret);
        }

        $this->client = new Client([
            'base_uri' => self::BASE_URI,
        ]);
    }

    /**
     * Get client id.
     *
     * @param  string   $clientId Twitch client id
     * @return string
     */
    public function getClientId(): string
    {
        if ( ! $this->clientId) {
            throw new RequestRequiresClientIdException;
        }

        return $this->clientId;
    }

    /**
     * Set client id.
     *
     * @param  string $clientId Twitch client id
     * @return void
     */
    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * Fluid client id setter.
     *
     * @param  string $clientId Twitch client id.
     * @return self
     */
    public function withClientId(string $clientId): self
    {
        $this->setClientId($clientIdcl);

        return $this;
    }

    /**
     * Get client secret.
     *
     * @param  string   $clientSecret Twitch client secret
     * @return string
     */
    public function getClientSecret(): string
    {
        if ( ! $this->clientSecret) {
            throw new RequestRequiresClientIdException;
        }

        return $this->clientSecret;
    }

    /**
     * Set client secret.
     *
     * @param  string $clientSecret Twitch client secret
     * @return void
     */
    public function setClientSecret(string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Fluid client secret setter.
     *
     * @param  string $clientSecret Twitch client secret
     * @return self
     */
    public function withClientSecret(string $clientSecret): self
    {
        $this->setClientSecret();

        return $this;
    }

    /**
     * Get OAuth token.
     *
     * @return string        Twitch token
     * @return string|null
     */
    public function getToken()
    {
        if ( ! $this->token) {
            throw new RequestRequiresAuthenticationException;
        }

        return $this->token;
    }

    /**
     * Set OAuth token.
     *
     * @param  string $token Twitch OAuth token
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * Fluid OAuth token setter.
     *
     * @param  string $token Twitch OAuth token
     * @return self
     */
    public function withToken(string $token): self
    {
        $this->setToken($token);

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
     * Build query & execute.
     *
     * @param  string $method     HTTP method
     * @param  string $path       Query path
     * @param  array  $parameters Query parameters
     * @param  mixed  $token      Token String or true/false to obtain by setToken method
     * @return Result Result object
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
     * Execute query.
     *
     * @param  string   $method   HTTP method
     * @param  string   $uri      Query path
     * @param  array    $headers  Query headers
     * @param  mixed    $jsonBody JSON Body
     * @return Result
     */
    private function executeQuery(string $method, string $uri, array $headers, Paginator $paginator = null, $jsonBody = null): Result
    {
        try {
            $request = new Request($method, $uri, $headers, $jsonBody);

            $response = $this->client->send($request);

            $result = new Result($response, null, $paginator);
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
     * Generate URL for API.
     *
     * @param  string      $url        Query uri
     * @param  null|string $token      Auth token, if required
     * @param  array       $parameters Query parameters
     * @return string      Full query url
     */
    private function generateUrl(string $url, array $parameters): string
    {
        foreach ($parameters as $optionKey => $option) {
            $data =  ! is_array($option) ? [$option] : $option;

            foreach ($data as $key => $value) {
                $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . $optionKey . '=' . $value;
            }
        }

        return $url;
    }

    /**
     * Generate headers.
     *
     * @param  bool    $json Body is JSON
     * @return array
     */
    public function generateHeaders(bool $json = false): array
    {
        $headers = [
            'Client-ID' => $this->getClientId(),
        ];

        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        if ($json) {
            $headers['Content-Type'] = 'application/json';
        }

        return $headers;
    }
}
