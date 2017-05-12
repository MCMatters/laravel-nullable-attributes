<?php

declare(strict_types = 1);

namespace McMatters\NullableAttributes\Traits;

use Illuminate\Database\Eloquent\Model;

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
        $nullableAttributes = array_get(
            config('nullable-attributes.attributes'),
            get_class($this),
            []
        );

        foreach ($nullableAttributes as $attribute) {
            $modelAttribute = $this->getAttribute($attribute);
            if ((is_array($modelAttribute) && empty($modelAttribute)) ||
                (is_string($modelAttribute) && trim($modelAttribute) === '')
            ) {
                $this->setAttribute($attribute, null);
            }
        }
    }
}
