<?php

namespace romanzipp\Twitch;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use romanzipp\Twitch\Concerns\Api;
use romanzipp\Twitch\Concerns\ClientCredentialsTrait;
use romanzipp\Twitch\Concerns\Validation\ValidationTrait;
use romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException;
use romanzipp\Twitch\Objects\Paginator;

class Twitch
{
    use ClientCredentialsTrait;
    use ValidationTrait;
    use Api\OAuthTrait;
    use Api\AdsTrait;
    use Api\AnalyticsTrait;
    use Api\BitsTrait;
    use Api\ChannelPoints;
    use Api\ClipsTrait;
    use Api\EntitlementsTrait;
    use Api\EventSubTrait;
    use Api\ExtensionsTrait;
    use Api\GamesTrait;
    use Api\HypeTrainTrait;
    use Api\PollsTrait;
    use Api\PredictionsTrait;
    use Api\SearchTrait;
    use Api\StreamsTrait;
    use Api\UsersTrait;
    use Api\VideosTrait;
    use Api\SubscriptionsTrait;
    use Api\TagsTrait;
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
     * @var \romanzipp\Twitch\Objects\Paginator
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

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function withClientId(string $clientId): self
    {
        $this->setClientId($clientId);

        return $this;
    }

    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    public function setClientSecret(string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    public function withClientSecret(string $clientSecret): self
    {
        $this->setClientSecret($clientSecret);

        return $this;
    }

    public function getRedirectUri(): ?string
    {
        return $this->redirectUri;
    }

    public function setRedirectUri(string $redirectUri): void
    {
        $this->redirectUri = $redirectUri;
    }

    public function withRedirectUri(string $redirectUri): self
    {
        $this->setRedirectUri($redirectUri);

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function withToken(string $token): self
    {
        $this->setToken($token);

        return $this;
    }

    public function setRequestClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     *
     * @return \romanzipp\Twitch\Result
     */
    public function get(string $path = '', array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->query('GET', $path, $parameters, $paginator);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     * @param array|null $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function post(string $path = '', array $parameters = [], Paginator $paginator = null, array $body = null): Result
    {
        return $this->query('POST', $path, $parameters, $paginator, $body);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     * @param array|null $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function put(string $path = '', array $parameters = [], Paginator $paginator = null, array $body = null): Result
    {
        return $this->query('PUT', $path, $parameters, $paginator, $body);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     * @param array|null $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function patch(string $path = '', array $parameters = [], Paginator $paginator = null, array $body = null): Result
    {
        return $this->query('PATCH', $path, $parameters, $paginator, $body);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator
     * @param array|null $body
     *
     * @return \romanzipp\Twitch\Result
     */
    public function delete(string $path = '', array $parameters = [], Paginator $paginator = null, array $body = null): Result
    {
        return $this->query('DELETE', $path, $parameters, $paginator, $body);
    }

    /**
     * Prepare & execute the query.
     *
     * @param string $method HTTP method
     * @param string $path Query path
     * @param array $parameters Query parameters
     * @param \romanzipp\Twitch\Objects\Paginator|null $paginator Paginator instance
     * @param array|null $body JSON body
     *
     * @throws \romanzipp\Twitch\Exceptions\RequestRequiresAuthenticationException
     * @throws \romanzipp\Twitch\Exceptions\OAuthTokenRequestException
     * @throws \GuzzleHttp\Exception\RequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return \romanzipp\Twitch\Result Result instance
     */
    public function query(string $method = 'GET', string $path = '', array $parameters = [], Paginator $paginator = null, array $body = null): Result
    {
        if ( ! $this->isAuthenticationUri($path) && null === $this->getToken() && $this->shouldFetchClientCredentials()) {
            $token = $this->getClientCredentials();

            if (null === $token) {
                throw new RequestRequiresAuthenticationException('The request requires an OAuth access token');
            }

            $this->setToken($token->accessToken);
        }

        if (null !== $paginator) {
            $parameters[$paginator->action] = $paginator->cursor();
        }

        $headers = $this->buildHeaders();

        if (null !== $body) {
            $headers['Content-Type'] = 'application/json';
        }

        try {
            $response = $this->client->request($method, $path, [
                'headers' => $headers,
                'query' => $this->buildQuery($parameters),
                'json' => $body,
            ]);
        } catch (RequestException $exception) {
            $response = $exception->getResponse();

            if (null === $response) {
                throw $exception;
            }
        }

        $result = new Result($response, $exception ?? null);

        if ($this->shouldCacheClientCredentials() && $result->isOAuthError()) {
            $this->clearClientCredentialsToken();
        }

        return $result;
    }

    /**
     * Build query with support for multiple same first-dimension keys.
     *
     * @param array $query
     *
     * @return string
     */
    private function buildQuery(array $query): string
    {
        $parts = [];

        foreach ($query as $name => $value) {
            $value = (array) $value;

            array_walk_recursive($value, static function ($value) use (&$parts, $name) {
                $parts[] = sprintf('%s=%s', urlencode($name), urlencode($value));
            });
        }

        return implode('&', $parts);
    }

    /**
     * Build headers for request.
     *
     * @return array
     */
    private function buildHeaders(): array
    {
        $headers = [
            'Client-ID' => $this->getClientId(),
        ];

        if (null !== $this->getToken()) {
            $headers['Authorization'] = "Bearer {$this->getToken()}";
        }

        return $headers;
    }
}
