<?php

declare(strict_types = 1);

if (!function_exists('get_model_nullable_attributes')) {
    /**
     * @param string $modelName
     * @return array
     */
    function get_model_nullable_attributes(string $modelName): array
    {
        static $manager;

        if (null === $manager) {
            if (!class_exists('DB')) {
                return [];
            }
            $manager = \DB::getDoctrineSchemaManager();
        }

        $attributes = [];
        $reflection = new ReflectionClass($modelName);
        if ($reflection->isInstantiable() &&
            $reflection->isSubclassOf(\Illuminate\Database\Eloquent\Model::class)
        ) {
            $table = (new $modelName)->getTable();
            /** @var array $columns */
            $columns = $manager->tryMethod('listTableColumns', $table);
            if (!$columns) {
                return [];
            }
            foreach ($columns as $column) {
                if (!$column->getNotnull() && null === $column->getDefault()) {
                    $attributes[] = $column->getName();
                }
            }
        }
        return $attributes;
    }
}
