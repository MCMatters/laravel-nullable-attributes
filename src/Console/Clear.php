<?php

declare(strict_types = 1);

namespace McMatters\NullableAttributes\Console;

use File;
use Illuminate\Console\Command;

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
     * Handle the command.
     */
    public function handle()
    {
        $fileName = storage_path('app/nullable_attributes.php');
        File::delete($fileName);
        $this->info("Successfully removed {$fileName}");
    }
}
