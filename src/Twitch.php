<?php

namespace romanzipp\Twitch;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use romanzipp\Twitch\Concerns\Api;
use romanzipp\Twitch\Concerns\AuthenticationTrait;
use romanzipp\Twitch\Concerns\Validation\ValidationTrait;
use romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException;
use romanzipp\Twitch\Exceptions\RequestRequiresClientIdException;
use romanzipp\Twitch\Exceptions\RequestRequiresClientSecretException;
use romanzipp\Twitch\Exceptions\RequestRequiresRedirectUriException;
use romanzipp\Twitch\Helpers\Paginator;

class Twitch
{
    use AuthenticationTrait;
    use ValidationTrait;

    use Api\OAuthTrait;
    use Api\AdsTrait;
    use Api\AnalyticsTrait;
    use Api\BitsTrait;
    use Api\ClipsTrait;
    use Api\EntitlementsTrait;
    use Api\ExtensionsTrait;
    use Api\FollowsTrait;
    use Api\GamesTrait;
    use Api\SearchTrait;
    use Api\StreamsMetadataTrait;
    use Api\StreamsTrait;
    use Api\UsersTrait;
    use Api\VideosTrait;
    use Api\SubscriptionsTrait;
    use Api\ModerationTrait;
    use Api\WebhooksTrait;

    public const BASE_URI = 'https://api.twitch.tv/helix/';

    public const OAUTH_BASE_URI = 'https://id.twitch.tv/oauth2/';

    /**
     * Guzzle is used to make http requests.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Paginator instance.
     *
     * @var \romanzipp\Twitch\Helpers\Paginator
     */
    protected $paginator;

    /**
     * Twitch OAuth token.
     *
     * @var string|null
     */
    protected $token = null;

    /**
     * Twitch Client ID.
     *
     * @var string|null
     */
    protected $clientId = null;

    /**
     * Twitch Client Secret.
     *
     * @var string|null
     */
    protected $clientSecret = null;

    /**
     * Twitch OAuth Redirect URI.
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

        if ($redirectUri = config('twitch-api.redirect_url')) {
            $this->setRedirectUri($redirectUri);
        }

        $this->client = new Client([
            'base_uri' => self::BASE_URI,
        ]);
    }

    /**
     * Check if a Client ID has been set.
     *
     * @return bool
     */
    public function hasClientId(): bool
    {
        return ! empty($this->clientId);
    }

    /**
     * Get client id.
     *
     * @return string
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresClientIdException
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
     * @param string $clientId Twitch client id
     * @return void
     */
    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * Fluid client id setter.
     *
     * @param string $clientId Twitch client id.
     * @return self
     */
    public function withClientId(string $clientId): self
    {
        $this->setClientId($clientId);

        return $this;
    }

    /**
     * Check if a Client Secret has been set.
     *
     * @return bool
     */
    public function hasClientSecret(): bool
    {
        return ! empty($this->clientSecret);
    }

    /**
     * Get client secret.
     *
     * @return string
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresClientSecretException
     */
    public function getClientSecret(): string
    {
        if ( ! $this->clientSecret) {
            throw new RequestRequiresClientSecretException;
        }

        return $this->clientSecret;
    }

    /**
     * Set client secret.
     *
     * @param string $clientSecret Twitch client secret
     * @return void
     */
    public function setClientSecret(string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Fluid client secret setter.
     *
     * @param string $clientSecret Twitch client secret
     * @return self
     */
    public function withClientSecret(string $clientSecret): self
    {
        $this->setClientSecret($clientSecret);

        return $this;
    }

    /**
     * Check if a Redirect URI has been set.
     *
     * @return bool
     */
    public function hasRedirectUri(): bool
    {
        return ! empty($this->redirectUri);
    }

    /**
     * Get Redirect URI.
     *
     * @return string
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresRedirectUriException
     */
    public function getRedirectUri(): string
    {
        if ( ! $this->redirectUri) {
            throw new RequestRequiresRedirectUriException;
        }

        return $this->redirectUri;
    }

    /**
     * Set Redirect URI.
     *
     * @param string $redirectUri
     * @return void
     */
    public function setRedirectUri(string $redirectUri): void
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * Fluid redirect url setter.
     *
     * @param string $redirectUri
     * @return self
     */
    public function withRedirectUri(string $redirectUri): self
    {
        $this->setRedirectUri($redirectUri);

        return $this;
    }

    /**
     * Check if a OAuth token has been set.
     *
     * @return bool
     */
    public function hasToken(): bool
    {
        return ! empty($this->token);
    }

    /**
     * Get OAuth token.
     *
     * @return string        Twitch token
     * @return string|null
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException
     */
    public function getToken(): ?string
    {
        if ( ! $this->token) {
            throw new RequestRequiresAuthenticationException;
        }

        return $this->token;
    }

    /**
     * Set OAuth token.
     *
     * @param string $token Twitch OAuth token
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * Fluid OAuth token setter.
     *
     * @param string $token Twitch OAuth token
     * @return self
     */
    public function withToken(string $token): self
    {
        $this->setToken($token);

        return $this;
    }

    /**
     * Set the guzzle client.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function setRequestClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator
     * @return \romanzipp\Twitch\Result
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresClientIdException
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException
     */
    public function get(string $path = '', array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->query('GET', $path, $parameters, $paginator);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator
     * @param array|null $body
     * @return \romanzipp\Twitch\Result
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresClientIdException
     */
    public function post(string $path = '', array $parameters = [], Paginator $paginator = null, array $body = null): Result
    {
        return $this->query('POST', $path, $parameters, $paginator, $body);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator
     * @param array|null $body
     * @return \romanzipp\Twitch\Result
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresClientIdException
     */
    public function put(string $path = '', array $parameters = [], Paginator $paginator = null, array $body = null): Result
    {
        return $this->query('PUT', $path, $parameters, $paginator, $body);
    }

    /**
     * Prepare & execute the query.
     *
     * @param string $method HTTP method
     * @param string $path Query path
     * @param array $parameters Query parameters
     * @param \romanzipp\Twitch\Helpers\Paginator|null $paginator Paginator instance
     * @param array|null $body JSON body
     *
     * @return \romanzipp\Twitch\Result Result instance
     *
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresClientIdException
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function query(string $method = 'GET', string $path = '', array $parameters = [], Paginator $paginator = null, array $body = null): Result
    {
        if ($paginator !== null) {
            $parameters[$paginator->action] = $paginator->cursor();
        }

        if ( ! $this->isAuthenticationUri($path) && ! $this->hasToken() && $this->shouldFetchClientCredentials()) {

            $token = $this->getClientCredentials();

            if ($token === null) {
                throw new RequestRequiresAuthenticationException('The request requires an OAuth access token');
            }

            $this->setToken($token->accessToken);
        }

        $jsonBody = null;

        if ($body !== null) {
            $jsonBody = json_encode($body);
        }

        try {
            /** @var \GuzzleHttp\Psr7\Response $response */
            $response = $this->client->request($method, $path, [
                'headers' => $this->buildHeaders($jsonBody !== null),
                'query' => $this->buildQuery($parameters),
                'json' => $jsonBody,
            ]);

            $result = new Result($response, null);

        } catch (RequestException $exception) {

            if ( ! $response = $exception->getResponse()) {
                throw $exception;
            }

            $result = new Result($response, $exception);
        }

        $result->twitch = $this;

        return $result;
    }

    /**
     * Build query with support for multiple same first-dimension keys.
     *
     * @param array $query
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
     * @param bool $json Body is JSON
     * @return array
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresClientIdException
     * @noinspection PhpDocMissingThrowsInspection
     */
    private function buildHeaders(bool $json = false): array
    {
        $headers = [
            'Client-ID' => $this->getClientId(),
        ];

        if ($this->hasToken()) {
            $headers['Authorization'] = sprintf('Bearer %s', $this->getToken());
        }

        if ($json) {
            $headers['Content-Type'] = 'application/json';
        }

        return $headers;
    }
}
