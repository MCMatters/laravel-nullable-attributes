<?php

declare(strict_types=1);

namespace McMatters\NullableAttributes\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

use function get_class, is_array, is_string, trim;

use const null;

/**
 * Class NullableAttributesTrait
 *
 * @package McMatters\NullableAttributes\Traits
 */
trait NullableAttributesTrait
{
    /**
     * Boot trait.
     */
    public static function bootNullableAttributesTrait()
    {
        self::saving(function (Model $model) {
            $model->convertEmptyToNullableAttributes();
        });
    }

    /**
     * @return void
     */
    protected function convertEmptyToNullableAttributes()
    {
        /** @var array $nullableAttributes */
        $nullableAttributes = Arr::get(
            Config::get('nullable-attributes.attributes'),
            get_class($this),
            []
        );

        foreach ($nullableAttributes as $attribute) {
            $modelAttribute = $this->getAttribute($attribute);

            if (
                (is_array($modelAttribute) && empty($modelAttribute)) ||
                (is_string($modelAttribute) && trim($modelAttribute) === '')
            ) {
                $this->setAttribute($attribute, null);
            }
        }
    }
}
