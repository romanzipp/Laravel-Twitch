# Laravel Twitch

[![Latest Stable Version](https://img.shields.io/packagist/v/romanzipp/laravel-twitch.svg?style=flat-square)](https://packagist.org/packages/romanzipp/laravel-twitch)
[![Total Downloads](https://img.shields.io/packagist/dt/romanzipp/laravel-twitch.svg?style=flat-square)](https://packagist.org/packages/romanzipp/laravel-twitch)
[![License](https://img.shields.io/packagist/l/romanzipp/laravel-twitch.svg?style=flat-square)](https://packagist.org/packages/romanzipp/laravel-twitch)
[![Code Quality](https://img.shields.io/scrutinizer/g/romanzipp/laravel-twitch.svg?style=flat-square)](https://scrutinizer-ci.com/g/romanzipp/laravel-twitch/?branch=master)
[![GitHub Build Status](https://img.shields.io/github/workflow/status/romanzipp/Laravel-Twitch/Tests?style=flat-square)](https://github.com/romanzipp/Laravel-Twitch/actions)

PHP Twitch Helix API Wrapper for Laravel 5+

## ⚠️ Changes on May 01, 2020

Since May 01, 2020, Twitch requires all requests to contain a valid OAuth Access Token.
This can be achieved by requesting an OAuth Token using the [Client Credentials Flow](https://dev.twitch.tv/docs/authentication/getting-tokens-oauth#oauth-client-credentials-flow).

If you don't handle this by yourself, be sure to enable the built-in Access Token generation feature via the `oauth_client_credentials.auto_generate` configuration entry.

You will need define a valid **Client ID** and **Client Secret** via your config or the available setters! See the [full config](https://github.com/romanzipp/Laravel-Twitch/blob/master/config/twitch-api.php) for more details.

## Table of contents

1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Examples](#examples)
4. [Documentation](#documentation)
5. [Upgrading to 2.0](#upgrading)
6. [Development](#Development)

## Installation

```
composer require romanzipp/laravel-twitch
```

**If you use Laravel 5.5+ you are already done, otherwise continue.**

Add Service Provider to your `app.php` configuration file:

```php
romanzipp\Twitch\Providers\TwitchServiceProvider::class,
```

## Configuration

Copy configuration to config folder:

```
$ php artisan vendor:publish --provider="romanzipp\Twitch\Providers\TwitchServiceProvider"
```

Add environmental variables to your `.env`

```
TWITCH_HELIX_KEY=
TWITCH_HELIX_SECRET=
TWITCH_HELIX_REDIRECT_URI=http://localhost
```

## Examples

### Basic

```php
$twitch = new romanzipp\Twitch\Twitch;

$twitch->setClientId('abc123');

// Get User by Username
$result = $twitch->getUserByName('herrausragend');

// Check, if the query was successful
if ( ! $result->success()) {
    return null;
}

// Shift result to get single user data
$user = $result->shift();

return $user->id;
```

### Setters

```php
$twitch = new romanzipp\Twitch\Twitch;

$twitch->setClientId('abc123');
$twitch->setClientSecret('abc456');
$twitch->setToken('abcdef123456');

$twitch = $twitch->withClientId('abc123');
$twitch = $twitch->withClientSecret('abc123');
$twitch = $twitch->withToken('abcdef123456');
```

### OAuth Tokens

```php
$twitch = new romanzipp\Twitch\Twitch;

$twitch->setClientId('abc123');
$twitch->setToken('abcdef123456');

$result = $twitch->getUserByName('herrausragend');
```

### OAuth Client Credentials Flow

Since May 01, 2020, every request requires an OAuth token which can be issued using the [OAuth Client Credentials Flow](https://dev.twitch.tv/docs/authentication/getting-tokens-oauth/#oauth-client-credentials-flow).

```php
use romanzipp\Twitch\Enums\GrantType;

$twitch = new romanzipp\Twitch\Twitch;

$twitch->setClientId('abc123');
$twitch->setClientSecret('def123');
$twitch->setToken('abcdef123456');

$result = $twitch->getOAuthToken(null, GrantType::CLIENT_CREDENTIALS, ['user:read']);

if ( ! $result->success()) {
    return;
}

$accessToken = $result->data()->access_token;
```

### Pagination

The Twitch API returns a `paginator` field with paginated results like `/streams`, `/follows`or `/games`. To jump between pages, the given cursor must be appended to the following query using the direction attributes `after` or `before`.

In this example, we will fetch a set of streams and use the provided cursor to switch to the next/previous set of data.

❗️ To prevent infinite loops or errors, use the `Result::hasMoreResults()` method to check if there are more results available.

```php
$twitch = new romanzipp\Twitch\Twitch;

// Page 1
$firstResult = $twitch->getStreams(['language' => 'de']);

// Page 2
$secondResult = $twitch->getStreams(['language' => 'de'], $firstResult->next());

// Page 1 (again)
$thirdResult = $twitch->getStreams(['language' => 'de'], $secondResult->back());
```

### Facade

```php
use romanzipp\Twitch\Facades\Twitch;

Twitch::withClientId('abc123')->withToken('abcdef123456')->getAuthedUser();
```

### Pagination Loop Example

This example fetches all Twitch Games and stores them into a database.

```php
$twitch = new romanzipp\Twitch\Twitch;

do {
    $nextCursor = null;

    // If this is not the first iteration, get the page cursor to the next set of results
    if (isset($result)) {
        $nextCursor = $result->next();
    }

    // Query the API with an optional cursor to the next results page
    $result = $twitch->getTopGames(['first' => 100], $nextCursor);

    foreach ($result->data as $item) {
        // Process the games result data
    }

    // Continue until there are no results left
} while ($result->hasMoreResults());
```

### Insert user objects

The new API does not include the user objects in endpoints like followers or bits.

```json
[
  {
    "from_id": "123456",
    "to_id": "654321",
    "followed_at": "2018-01-01 12:00:00"
  }
]
```

You can just call the [insertUsers](https://github.com/romanzipp/Laravel-Twitch/blob/master/src/Result.php#L233) method to insert all user data identified by `from_id` into `from_user`

```php
$result = $twitch->getFollowsTo(654321);

$result->insertUsers('from_id', 'from_user');
```

**New Result data:**

```json
[
  {
    "from_id": "123456",
    "to_id": "654321",
    "followed_at": "2018-01-01 12:00:00",
    "from_user": {
      "id": "123456",
      "display_name": "HerrAusragend",
      "login": "herrausragend"
    }
  }
]
```

## Documentation

**Twitch Helix API Documentation: https://dev.twitch.tv/docs/api/reference**

<!-- GENERATED-DOCS -->

[**OAuth Scopes Enums**](https://github.com/romanzipp/Laravel-Twitch/blob/master/src/Enums/Scope.php)

## Upgrading

- [Upgrade from 1.0 to 2.0](https://github.com/romanzipp/Laravel-Twitch/releases/tag/2.0.0)

## Development

#### Run Tests

```shell
composer test
```

```shell
CLIENT_ID=xxxx composer test
```

```shell
CLIENT_ID=xxxx CLIENT_SECRET=xxxx composer test
```

#### Generate Documentation

```shell
composer docs
```

---

Join the Twitch Dev Discord!

[![Discord](https://discordapp.com/api/guilds/504015559252377601/embed.png?style=banner2)](https://discord.gg/YAMGgZT)

[![Discord](https://discordapp.com/api/guilds/325552783787032576/embed.png?style=banner2)](https://discord.gg/8NXaEyV)
