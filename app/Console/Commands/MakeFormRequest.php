<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MakeFormRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:custom-request {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $directoryPath = app_path("Http/Requests");
        $parts = explode('/', $name);
        $lastPart = array_pop($parts) . "Request";
        
        foreach ($parts as $part) {
            $directoryPath .= DIRECTORY_SEPARATOR . Str::studly($part);
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true);
            }
        }

        $filename = $directoryPath . DIRECTORY_SEPARATOR . "{$lastPart}.php";

        if (file_exists($filename)) {
            $this->error('Request form already exists!');
            return;
        }

        $stub = <<<'STUB'
        <?php

        namespace App\Http\Requests{{Namespace}};

        use App\Http\Requests\Base\BaseRequestForm;

        class {{RequestName}} extends BaseRequestForm
        {
            

            public function rules()
            {
                return [

                ];
            }
        }
        STUB;

        $stub = str_replace('{{Namespace}}', count($parts) ==0 ?null : "\\". implode('\\', $parts), $stub);
        $stub = str_replace('{{RequestName}}', $lastPart, $stub);

        file_put_contents($filename, $stub);

        $this->info("Custom request form created successfully in 'App\Http\Requests\\{$name}' directory!");
    }
}
