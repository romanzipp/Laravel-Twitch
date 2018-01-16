# Laravel Twitch

[![Latest Stable Version](https://poser.pugx.org/romanzipp/laravel-twitch/version)](https://packagist.org/packages/romanzipp/laravel-twitch)
[![Total Downloads](https://poser.pugx.org/romanzipp/laravel-twitch/downloads)](https://packagist.org/packages/romanzipp/laravel-twitch)
[![License](https://poser.pugx.org/romanzipp/laravel-twitch/license)](https://packagist.org/packages/romanzipp/laravel-twitch)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/romanzipp/Laravel-Twitch/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/romanzipp/Laravel-Twitch/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/romanzipp/Laravel-Twitch/badges/build.png?b=master)](https://scrutinizer-ci.com/g/romanzipp/Laravel-Twitch/build-status/master)

PHP Twitch API Wrapper for Laravel 5+

**NOTICE: This library uses the latest Twitch API which ist not fully featured yet**

## Installation

```
composer require romanzipp/laravel-twitch:dev-master
```

Or add `romanzipp/laravel-twitch` to your `composer.json`

```
"romanzipp/laravel-twitch": "dev-master"
```

Run composer update to pull down the latest version.

## Example

### With Laravel dependency injection

```php
Route::get('/', function (\romanzipp\Twitch\Twitch $twitch) {

    // Get User by Username
    $userResult = $twitch->getUserByName('staiy');

    // Check, if the query was successfull
    if ($userResult->success) {
        $user = $userResult->shift();
    }

    // Use Paginator

    // Fetch first set of followers
    $followsResult = $twitch->getFollowsTo(48865821);

    // Fetch next set of followers
    $nextFollowsResult = $twitch->getFollowsTo(48865821, $followsResult->next());
});
```

## Documentation

Available in [Wiki](https://github.com/romanzipp/Laravel-Twitch/wiki/Full-reference) section

**Twitch API Documentation: https://dev.twitch.tv/docs/api/reference**
