<?php

declare(strict_types = 1);

namespace McMatters\NullableAttributes\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\ClassLoader\ClassMapGenerator;

/**
 * Class Cache
 *
 * @package McMatters\NullableAttributes\Console
 */
class Cache extends Command
{
    /**
     * @var string
     */
    protected $signature = 'nullable-attributes:cache';

    /**
     * @var string
     */
    protected $description = 'Cache nullable attributes of all models';

    /**
     * Handle the command.
     */
    public function handle()
    {
        $models = $this->getModels();
        $nullables = $this->findNullables($models);

        $content = '<?php'.PHP_EOL.'return '.var_export($nullables, true).';';
        $fileName = config('nullable-attributes.cache');
        File::put($fileName, $content);

        $this->info("Successfully written to the {$fileName}");
    }

    /**
     * @return array
     */
    protected function getModels(): array
    {
        $models = [];
        $dir = config('nullable-attributes.models');

        foreach (ClassMapGenerator::createMap($dir) as $model => $path) {
            try {
                $reflection = new ReflectionClass($model);
            } catch (ReflectionException $e) {
                continue;
            }

            if ($reflection->isInstantiable() &&
                !$reflection->isSubclassOf(Pivot::class) &&
                $reflection->isSubclassOf(Model::class)
            ) {
                $models[] = $model;
            }
        }

        return $models;
    }

    /**
     * @param array $models
     *
     * @return array
     */
    protected function findNullables(array $models): array
    {
        $nullables = [];

        foreach ($models as $model) {
            $nullables[$model] = $this->getNullablesFromDb($model);
        }

        return $nullables;
    }

    /**
     * @param string $model
     *
     * @return array
     */
    protected function getNullablesFromDb(string $model): array
    {
        static $manager;

        if (null === $manager) {
            $manager = DB::getDoctrineSchemaManager();
        }

        $attributes = [];

        $table = (new $model)->getTable();
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

        return $attributes;
    }
}
