<?php

namespace McMatters\Traits;

/**
 * Class NullableAttributesTrait
 *
 * @package McMatters\Traits
 */
trait NullableAttributesTrait
{
    /**
     * @var array
     */
    protected static $nullableAttributes = [];

    /**
     * Boot trait.
     */
    public static function bootNullableAttributesTrait()
    {
        static $cache;

        if (null === $cache) {
            $cacheFile = storage_path('app/nullable_attributes.php');
            $cache = file_exists($cacheFile) ? include $cacheFile : false;
            $cache = is_array($cacheFile) ? $cacheFile : false;
        }

        if (false !== $cache && isset($cache[static::class])) {
            self::$nullableAttributes = $cache[static::class];
            return;
        } else {
            self::$nullableAttributes[static::class] = get_model_nullable_attributes(static::class);
        }
    }

    /**
     * Workaround for empty values
     *
     * @param string $key
     * @param mixed $val
     */
    public function setAttribute($key, $val)
    {
        if (is_string($val) &&
            !($val = trim($val)) &&
            in_array($key, static::$nullableAttributes[static::class], true)
        ) {
            $val = null;
        }
        return parent::setAttribute($key, $val);
    }
}
