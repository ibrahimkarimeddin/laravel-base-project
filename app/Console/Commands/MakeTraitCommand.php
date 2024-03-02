<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeTraitCommand extends Command
{
    protected $signature = 'make:custom-trait {name : The name of the trait (e.g., auth/AuthTrait)}';

    protected $description = 'Create a new trait';

    public function handle()
    {
        $name = $this->argument('name');

        [$directory, $traitName] = explode('/', $name);

        $traitPath = app_path('Traits') . '/' . $directory;

        if (!File::isDirectory($traitPath)) {
            File::makeDirectory($traitPath, 0755, true);
        }

        $traitFilePath = $traitPath . '/' . $traitName . '.php';

        if (File::exists($traitFilePath)) {
            $this->error('Trait already exists!');
            return;
        }

        $traitContent = <<<EOD
<?php

namespace App\Traits\\$directory;

trait $traitName
{
    // Your trait methods
}
EOD;

        File::put($traitFilePath, $traitContent);

        $this->info("Trait created successfully: $traitFilePath");
    }
}
