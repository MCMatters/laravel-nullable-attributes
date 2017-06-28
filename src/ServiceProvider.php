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
            $configPath => $this->configPath('nullable-attributes.php'),
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

    /**
     * @param string $file
     *
     * @return string
     */
    protected function configPath(string $file): string
    {
        if (function_exists('config_path')) {
            return config_path($file);
        }

        return base_path("config/{$file}");
    }
}
