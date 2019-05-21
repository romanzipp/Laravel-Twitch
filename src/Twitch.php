<?php

namespace romanzipp\Twitch;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException;
use romanzipp\Twitch\Exceptions\RequestRequiresClientIdException;
use romanzipp\Twitch\Exceptions\RequestRequiresRedirectUriException;
use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Traits\BitsTrait;
use romanzipp\Twitch\Traits\ClipsTrait;
use romanzipp\Twitch\Traits\ExtensionsTrait;
use romanzipp\Twitch\Traits\FollowsTrait;
use romanzipp\Twitch\Traits\GamesTrait;
use romanzipp\Twitch\Traits\OAuthTrait;
use romanzipp\Twitch\Traits\StreamsMetadataTrait;
use romanzipp\Twitch\Traits\StreamsTrait;
use romanzipp\Twitch\Traits\SubscriptionsTrait;
use romanzipp\Twitch\Traits\UsersTrait;
use romanzipp\Twitch\Traits\VideosTrait;
use romanzipp\Twitch\Traits\WebhooksTrait;

class Twitch
{
    use OAuthTrait;
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
    const OAUTH_BASE_URI = 'https://id.twitch.tv/oauth2/';

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
     * @var string|null
     */
    protected $token = null;

    /**
     * Twitch client id.
     *
     * @var string|null
     */
    protected $clientId = null;

    /**
     * Twitch client secret.
     *
     * @var string|null
     */
    protected $clientSecret = null;

    /**
     * Twitch OAuth redirect url.
     *
     * @var string|null
     */
    protected $redirectUri = null;

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

        if ($redirectUri = config('twitch-api.client_secret')) {
            $this->setredirectUri($redirectUri);
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
        $this->setClientId($clientId);

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
        $this->setClientSecret($clientSecret);

        return $this;
    }

    /**
     * Get redirect url.
     *
     * @return string
     */
    public function getRedirectUri(): string
    {
        if ( ! $this->redirectUri) {
            throw new RequestRequiresRedirectUriException;
        }

        return $this->redirectUri;
    }

    /**
     * Set redirect url.
     *
     * @param  string $redirectUri
     * @return self
     */
    public function setRedirectUri(string $redirectUri): void
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * Fluid redirect url setter.
     *
     * @param  string $redirectUri
     * @return self
     */
    public function withRedirectUri(string $redirectUri): self
    {
        $this->setRedirectUri($redirectUri);

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
     * @param  string     $method     HTTP method
     * @param  string     $path       Query path
     * @param  array      $parameters Query parameters
     * @param  Paginator  $paginator  Paginator object
     * @param  mixed|null $jsonBody   JSON data
     * @return Result     Result object
     */
    public function query(string $method = 'GET', string $path = '', array $parameters = [], Paginator $paginator = null, $jsonBody = null): Result
    {
        if ($paginator !== null) {
            $parameters[$paginator->action] = $paginator->cursor();
        }

        try {
            $response = $this->client->request($method, $path, [
                'headers' => $this->buildHeaders($jsonBody ? true : false),
                'query'   => $this->buildQuery($parameters),
                'json'    => $jsonBody ? $jsonBody : null,
            ]);

            $result = new Result($response, null, $paginator);

        } catch (RequestException $exception) {

            $result = new Result($exception->getResponse(), $exception, $paginator);
        }

        $result->twitch = $this;

        return $result;
    }

    /**
     * Build query with support for multiple smae first-dimension keys.
     *
     * @param  array    $query
     * @return string
     */
    public function buildQuery(array $query): string
    {
        $parts = [];

        foreach ($query as $name => $value) {

            $value = (array) $value;

            array_walk_recursive($value, function ($value) use (&$parts, $name) {
                $parts[] = urlencode($name) . '=' . urlencode($value);
            });
        }

        return implode('&', $parts);
    }

    /**
     * Build headers for request.
     *
     * @param  bool    $json Body is JSON
     * @return array
     */
    private function buildHeaders(bool $json = false): array
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
