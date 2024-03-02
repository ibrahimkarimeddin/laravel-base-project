<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

use function PHPSTORM_META\type;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:custom-service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $parts = explode('/', $name);
        $directoryPath = app_path("/Services");
        $lastPart = array_pop($parts) . "Service";


        foreach ($parts as $part) {
            $directoryPath .= DIRECTORY_SEPARATOR . Str::studly($part);
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true);
            }
        }

        $filename = $directoryPath . DIRECTORY_SEPARATOR . "{$lastPart}.php";


        // echo $model;

        if (file_exists($filename)) {
            $this->error('Service already exists!');
            return;
        }



        $stub = <<<'STUB'
        <?php

        namespace App\Services;

        class {{ClassName}}
         {

        }
        STUB;

        $stub = str_replace('{{ClassName}}', $lastPart, $stub);


        file_put_contents($filename, $stub);

        $this->info('Service created successfully!');

    }
}
