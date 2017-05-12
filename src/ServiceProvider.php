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
        $configPath = __DIR__.'/../config/nullable-attributes.php';

        $this->publishes([
            $configPath => config_path('nullable-attributes.php'),
        ], 'config');

        $this->mergeConfigFrom($configPath, 'nullable-attributes');

        $this->setNullableAttributes();
    }

    /**
     * Register methods.
     */
    public function register()
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

    /**
     * @return void
     */
    protected function setNullableAttributes()
    {
        $cacheFile = config('nullable-attributes.cache');
        $cache = file_exists($cacheFile) ? include $cacheFile : false;
        $attributes = is_array($cache) ? $cache : [];

        config(['nullable-attributes.attributes' => $attributes]);
    }
}
