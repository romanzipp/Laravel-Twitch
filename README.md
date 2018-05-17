# Laravel Twitch

[![Latest Stable Version](https://poser.pugx.org/romanzipp/laravel-twitch/version)](https://packagist.org/packages/romanzipp/laravel-twitch)
[![Total Downloads](https://poser.pugx.org/romanzipp/laravel-twitch/downloads)](https://packagist.org/packages/romanzipp/laravel-twitch)
[![License](https://poser.pugx.org/romanzipp/laravel-twitch/license)](https://packagist.org/packages/romanzipp/laravel-twitch)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/romanzipp/Laravel-Twitch/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/romanzipp/Laravel-Twitch/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/romanzipp/Laravel-Twitch/badges/build.png?b=master)](https://scrutinizer-ci.com/g/romanzipp/Laravel-Twitch/build-status/master)
[![StyleCI](https://styleci.io/repos/116135617/shield?branch=master&style=flat)](https://styleci.io/repos/116135617)

PHP Twitch API Wrapper for Laravel 5+

**NOTICE: This library uses the latest Twitch HELIX API which ist not fully featured yet**

Join the Twitch Dev Discord!

[![Discord](https://discordapp.com/api/guilds/325552783787032576/embed.png?style=banner2)](https://discord.gg/8NXaEyV)

## Installation

```
composer require romanzipp/laravel-twitch
```

Or add `romanzipp/laravel-twitch` to your `composer.json`

```
"romanzipp/laravel-twitch": "*"
```

Run composer update to pull the latest version.

**If you use Laravel 5.5+ you are already done, otherwise continue:**

1. Add Service Provider to your `app.php` configuration file:

```php
romanzipp\Twitch\Providers\TwitchServiceProvider::class,
```

## Configuration

Copy configuration to config folder:

```
$ php artisan vendor:publish --provider=romanzipp\Twitch\Providers\TwitchServiceProvider
```

Add environmental variables to your `.env`

```
TWITCH_HELIX_KEY=
TWITCH_HELIX_SECRET=
TWITCH_HELIX_REDIRECT_URI=http://localhost
```

## Example

### With Laravel dependency injection

```php
Route::get('/', function (\romanzipp\Twitch\Twitch $twitch) {

    // Get User by Username
    $userResult = $twitch->getUserByName('staiy');

    // Check, if the query was successfull
    if ($userResult->success()) {
        // Shift result to get single user data
        $user = $userResult->shift();

        echo $user['id'];
    }

    // Use Paginator

    // Fetch first set of followers
    $followsResult = $twitch->getFollowsTo(48865821);

    // Fetch next set of followers
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
            ['id' => $item->id],
            [
                'name' => $item->name,
                'box_art_url' => $item->box_art_url,
            ]
        );
    }

} while ($result->count() > 0);
```

## Documentation

Available in [Wiki](https://github.com/romanzipp/Laravel-Twitch/wiki/Full-reference) section

**Twitch API Documentation: https://dev.twitch.tv/docs/api/reference**
