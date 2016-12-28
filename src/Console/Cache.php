<?php

declare(strict_types = 1);

namespace McMatters\NullableAttributes\Console;

use File;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
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
        $models = $this->loadModels();
        $nullables = $this->findNullables($models);
        $content = '<?php'.PHP_EOL.'return '.var_export($nullables, true).';';
        $fileName = storage_path('app/nullable_attributes.php');
        File::put($fileName, $content);
        $this->info('Successfully written to the '.$fileName);
    }

    /**
     * @return array
     */
    protected function loadModels(): array
    {
        $models = [];
        $dir = config('nullable-attributes.folder');
        foreach (ClassMapGenerator::createMap($dir) as $model => $path) {
            $reflection = new ReflectionClass($model);
            if ($reflection->isInstantiable() &&
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
            $nullables[$model] = get_model_nullable_attributes($model);
        }

        return $nullables;
    }
}
