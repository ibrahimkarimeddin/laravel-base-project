<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
class MakeRepository extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:custom-repository {name} {--model=}';

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
        $model = $this->option('model');
        $directoryPath = app_path("/Repositories");
        $lastPart = array_pop($parts) . "Repository";


        foreach ($parts as $part) {
            $directoryPath .= DIRECTORY_SEPARATOR . Str::studly($part);
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true);
            }
        }

        $filename = $directoryPath . DIRECTORY_SEPARATOR . "{$lastPart}.php";


        // echo $model;

        if (file_exists($filename)) {
            $this->error('Repository already exists!');
            return;
        }

        if(!$model){

            $this->error('Please Select Model For The Repository');
            return ;
        }

        $stub = <<<'STUB'
        <?php

        namespace App\Repositories;
        use App\Repositories\Base\CrudBaseRepository ;
        use App\Models\{{Model}};

        class {{ClassName}} extends CrudBaseRepository
         {

            public function __construct() {
                parent::__construct(new {{Model}});

                $this->filterable = [

                    "search" =>[
                        'name'=>'string',
                        'category_id'=>'number'
                    ],
                    "sort" => [
                        'created_at' =>'desc'
                    ],
                    'custom'=> function($query){
                        $query->select('id');
                    },


                ];
                $this->relations = [];

            }
        }
        STUB;

        $stub = str_replace('{{ClassName}}', $lastPart, $stub);
        $stub2 = str_replace('{{Model}}', $model, $stub);


        file_put_contents($filename, $stub2);

        $this->info('Repository created successfully!');

    }
}
