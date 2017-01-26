<?php

declare(strict_types = 1);

namespace McMatters\NullableAttributes;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider
 *
 * @package McMatters
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Boot provider.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/nullable-attributes.php' => config_path('nullable-attributes.php'),
        ]);
    }

    /**
     * Register methods.
     */
    public function register()
    {
        $this->registerCommands();
    }

    /**
     * Register commands.
     */
    protected function registerCommands()
    {
        $this->app->singleton('command.nullable-attributes.cache', function () {
            return new Console\Cache();
        });
        $this->app->singleton('command.nullable-attributes.clear', function () {
            return new Console\Clear();
        });

        $this->commands([
            'command.nullable-attributes.cache',
            'command.nullable-attributes.clear',
        ]);
    }
}
