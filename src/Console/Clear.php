<?php

declare(strict_types=1);

namespace McMatters\NullableAttributes\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

/**
 * Class Clear
 *
 * @package McMatters\NullableAttributes\Console
 */
class Clear extends Command
{
    /**
     * @var string
     */
    protected $signature = 'nullable-attributes:clear';

    /**
     * @var string
     */
    protected $description = 'Clear cache of nullable attributes of all models';

    /**
     * @return void
     */
    public function handle()
    {
        $fileName = Config::get('nullable-attributes.cache');

        File::delete($fileName);

        $this->info("Successfully removed {$fileName}");
    }
}
