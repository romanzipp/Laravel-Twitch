<?php

namespace romanzipp\Twitch\Providers;

use Illuminate\Support\ServiceProvider;
use romanzipp\Twitch\Twitch;

class TwitchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/../config/twitch-api.php' => config_path('twitch-api.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/../config/twitch-api.php', 'twitch-api'
        );

        $this->app->singleton(Twitch::class, function () {
            return new Twitch();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Twitch::class];
    }
}
