<?php

declare(strict_types = 1);

namespace McMatters;

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
        $this->app['command.nullable-attributes.cache'] = $this->app->share(
            function () {
                return new McMatters\Console\Cache();
            }
        );
        $this->app['command.nullable-attributes.clear'] = $this->app->share(
            function () {
                return new McMatters\Console\Clear();
            }
        );

        $this->commands([
            'command.nullable-attributes.cache',
            'command.nullable-attributes.clear',
        ]);
    }
}
