<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeCustomInterface extends Command
{
    protected $signature = 'make:custom-interface {name}';

    protected $description = 'Create a custom interface';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');

        $segments = explode('/', $name);
        $interfaceName = array_pop($segments) . 'Interface';

        $interfaceNamespace = 'App\\Interfaces' . (count($segments) == "0" ?null :"\\".implode('\\', $segments));

        $interfaceContent = "<?php\n\nnamespace $interfaceNamespace;\n\ninterface $interfaceName\n{\n    // Define your interface methods here\n}\n";
        $directory = app_path('Interfaces/' . implode('/', $segments));

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = $directory . '/' . $interfaceName . '.php';

        if (file_exists($filePath)) {
            $this->error('Interface already exists!');
            return;
        }

        file_put_contents($filePath, $interfaceContent);

        $this->info('Interface created successfully: ' . $interfaceName);
    }
}
