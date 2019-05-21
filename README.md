# Laravel Twitch

[![Latest Stable Version](https://img.shields.io/packagist/v/romanzipp/laravel-twitch.svg?style=flat-square)](https://packagist.org/packages/romanzipp/laravel-twitch)
[![Total Downloads](https://img.shields.io/packagist/dt/romanzipp/laravel-twitch.svg?style=flat-square)](https://packagist.org/packages/romanzipp/laravel-twitch)
[![License](https://img.shields.io/packagist/l/romanzipp/laravel-twitch.svg?style=flat-square)](https://packagist.org/packages/romanzipp/laravel-twitch)
[![Code Quality](https://img.shields.io/scrutinizer/g/romanzipp/laravel-twitch.svg?style=flat-square)](https://scrutinizer-ci.com/g/romanzipp/laravel-twitch/?branch=master)
[![Scrutinizer Build Status](https://img.shields.io/scrutinizer/build/g/romanzipp/laravel-twitch.svg?style=flat-square)](https://scrutinizer-ci.com/g/romanzipp/laravel-twitch/build-status/master)
[![Travis Build Status](https://img.shields.io/travis/romanzipp/Laravel-Twitch/master.svg?style=flat-square)](https://travis-ci.org/romanzipp/Laravel-Twitch)

PHP Twitch API Wrapper for Laravel 5+

**NOTICE: This library uses the latest Twitch HELIX API which ist not fully featured yet**

Join the Twitch Dev Discord!

[![Discord](https://discordapp.com/api/guilds/504015559252377601/embed.png?style=banner2)](https://discord.gg/YAMGgZT)

[![Discord](https://discordapp.com/api/guilds/325552783787032576/embed.png?style=banner2)](https://discord.gg/8NXaEyV)

## Table of contents

1. [Installation](https://github.com/romanzipp/Laravel-Twitch#installation)
2. [Configuration](https://github.com/romanzipp/Laravel-Twitch#configuration)
3. [Examples](https://github.com/romanzipp/Laravel-Twitch#examples)
4. [Documentation](https://github.com/romanzipp/Laravel-Twitch#documentation)

## Installation

```
composer require romanzipp/laravel-twitch
```

Or add `romanzipp/laravel-twitch` to your `composer.json`

```
"romanzipp/laravel-twitch": "^1.0"
```

Run `composer install` to pull the latest version.

**If you use Laravel 5.5+ you are already done, otherwise continue:**

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

### With Laravel dependency injection

```php
use \romanzipp\Twitch\Twitch;

Route::get('/', function (Twitch $twitch) {

    // Get User by Username
    $userResult = $twitch->getUserByName('herrausragend');

    // Check, if the query was successfull
    if ($userResult->success()) {

        // Shift result to get single user data
        $user = $userResult->shift();

        echo $user->id;
    }

    // Fetch first set of followers
    $followsResult = $twitch->getFollowsTo(48865821);

    // Fetch next set of followers using the next() Result method
    $nextFollowsResult = $twitch->getFollowsTo(48865821, $followsResult->next());
});
```

### OAuth Tokens

```php
use \romanzipp\Twitch\Twitch;

// Create instance with OAuth Token
$twitch = new Twitch('843tvnbq35676unzrtvs78');

$userResult = $twitch->getAuthedUser();

if ($userResult->success()) {
    $user = $userResult->shift();
}

// Change OAuth Token on the fly
$twitch->setToken('j8uz457tzv5476v0qp');

// Pass Token to methods that accept OAuth Token as parameters
$userResult = $twitch->getAuthedUser('843tvnbq35676unzrtvs78');

```

### Pagination Loop Example

This example fetches all Twitch Games and stores them into a database.

```php
use \romanzipp\Twitch\Twitch;

$twitch = new Twitch;

do {
    $result = $twitch->getTopGames(['first' => 100], isset($result) ? $result->next() : null);

    foreach ($result->data as $item) {

        Game::updateOrCreate(
            [
                'id' => $item->id,
            ], [
                'name' => $item->name,
                'box_art_url' => $item->box_art_url,
            ]
        );
    }

} while ($result->count() > 0);
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

You can just call the [insertUsers](https://github.com/romanzipp/Laravel-Twitch/blob/master/src/Result.php#L217) method to insert all users.

```php
$result = $twitch->getFollowsTo($userId);
$result->insertUsers('from_id', 'from_user'); // Insert users identified by "from_id" to "from_user"
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

### Bits

```php
function getBitsLeaderboard(array $parameters = []): Result {}
```

### Clips

```php
function createClip(int $broadcaster): Result {}
function getClip(string $id): Result {}
function createEntitlementUrl(string $manifest, string $type = 'bulk_drops_grant'): Result {}
```

### Follows

```php
function getFollows(int $from = null, int $to = null, Paginator $paginator = null): Result {}
function getFollowsFrom(int $from, Paginator $paginator = null): Result {}
function getFollowsTo(int $to, Paginator $paginator = null): Result {}
```

### Games

```php
function getGames(array $parameters): Result {}
function getGameById(int $id): Result {}
function getGameByName(string $name): Result {}
function getGamesByIds(array $ids): Result {}
function getGamesByNames(array $names): Result {}
function getTopGames(array $parameters = [], Paginator $paginator = null): Result {}
```

### Streams Metadata

```php
function getStreamsMetadata(array $parameters = [], Paginator $paginator = null): Result {}
```

### Streams

```php
function getStreams(array $parameters = [], Paginator $paginator = null): Result {}
function getStreamsByUserId(int $id, array $parameters = [], Paginator $paginator = null): Result {}
function getStreamsByUserName(string $name, array $parameters = [], Paginator $paginator = null): Result {}
function getStreamsByUserIds(array $ids, array $parameters = [], Paginator $paginator = null): Result {}
function getStreamsByUserNames(array $names, array $parameters = [], Paginator $paginator = null): Result {}
function getStreamsByCommunity(int $id, array $parameters = [], Paginator $paginator = null): Result {}
function getStreamsByCommunities(int $ids, array $parameters = [], Paginator $paginator = null): Result {}
function getStreamsByGame(int $id, array $parameters = [], Paginator $paginator = null): Result {}
function getStreamsByGames(int $ids, array $parameters = [], Paginator $paginator = null): Result {}
```

### Users

```php
function getAuthedUser(string $token = null): Result {}
function getUsers(array $parameters): Result {}
function getUserById(int $id, array $parameters = []): Result {}
function getUserByName(string $name, array $parameters = []): Result {}
function getUsersByIds(array $ids, array $parameters = []): Result {}
function getUsersByNames(array $names, array $parameters = []): Result {}
function updateUser(string $description): Result {}
```

### Videos

```php
function getVideos(array $parameters, Paginator $paginator = null): Result {}
function getVideosById(int $id, array $parameters = [], Paginator $paginator = null): Result {}
function getVideosByUser(int $user, array $parameters = [], Paginator $paginator = null): Result {}
function getVideosByGame(int $game, array $parameters = [], Paginator $paginator = null): Result {}
```

### Subscriptions

```php
public function getSubscriptions(array $parameters = [], Paginator $paginator = null): Result {}
public function getUserSubscriptions(int $user, Paginator $paginator = null): Result {}
public function getBroadcasterSubscriptions(int $user, Paginator $paginator = null): Result {}
```

### Wehooks

```php
function subscribeWebhook(string $callback, string $topic, int $lease = null, string $secret = null): Result {}
function unsubscribeWebhook(string $callback, string $topic): Result {}
function getWebhookSubscriptions(array $parameters = []): Result {}
function webhookTopicStreamMonitor(int $user): string {}
function webhookTopicUserFollows(int $from): string {}
function webhookTopicUserGainsFollower(int $to): string {}
function webhookTopicUserFollowsUser(int $from, int $to): string {}
```

### Legacy

#### Root

```php
function legacyRoot(): Result {}
```

#### OAuth

```php
function legacyRefreshToken(string $refreshToken, string $clientSecret = null, string $scope = null): Result {}
```

## Enums

### [OAuth Scopes](https://github.com/romanzipp/Laravel-Twitch/blob/master/src/Enums/Scope.php)
