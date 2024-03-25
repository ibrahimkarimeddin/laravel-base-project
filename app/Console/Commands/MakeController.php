<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MakeController extends Command
{
    protected $signature = 'make:custom-controller {name} {--service=}';

    protected $description = 'Create a new controller class with custom content';

    public function handle()
    {
        $name = $this->argument('name');
        $service = $this->option('service');

        $fill_path_with_last_name = $name ;

        $controllerName = Str::studly($name) . 'Controller';
        $path = app_path("Http/Controllers/{$controllerName}.php");
        $directoryPath = app_path("Http/Controllers");
        $namespace  = null;

        $parts = explode('/', $name);

        $name = array_pop($parts) ;
        $controllerName = $name . "Controller";
        $lower_case_name_controller = strtolower($name);

        foreach ($parts as $part) {
            $directoryPath .= DIRECTORY_SEPARATOR . Str::studly($part);
            if(!$namespace){
                $namespace =  Str::studly($part);
            }else{
                $namespace .= DIRECTORY_SEPARATOR . Str::studly($part);

            }
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true);
            }
        }

        if (file_exists($path)) {
            $this->error('Controller already exists!');
            return;
        }

        $content = <<<'STUB'
        <?php

        namespace App\Http\Controllers{{NAMESPACE2}};
        use App\Http\Controllers\Controller;

        use App\Enums\ResponseEnum;
        use Illuminate\Http\Request;
        use App\Http\Requests\{{NAMESPACE}}\Create{{NAME}}Request;
        use App\Http\Requests\{{NAMESPACE}}\Delete{{NAME}}Request;
        use App\Http\Requests\{{NAMESPACE}}\GetOne{{NAME}}Request;
        use App\Http\Requests\{{NAMESPACE}}\Update{{NAME}}Request;
        use App\Http\Requests\{{NAMESPACE}}\GetAll{{NAME}}Request;

        use App\Http\Resources\{{NAMESPACE}}\GetAll{{NAME}}Resource;
        use App\Http\Resources\{{NAMESPACE}}\GetOne{{NAME}}Resource;
        class {{ControllerName}} extends Controller
        {

            public function __construct(private {{SERVICE}} $repository  ) {

            }
            public  function  getOne(GetOne{{NAME}}Request $request){

                $data = $this->repository->findByID($request->{{lower_case_name}}_id);
                $response = new GetOne{{NAME}}Resource($data);
                return $this->sendResponse(ResponseEnum::GET ,$response );
            }
            public  function  getAll(GetAll{{NAME}}Request $request){

                $data = $this->repository->getAll($is_pagenate = true ,  $request->per_page ?? 8 , $request->search );
                $response =  GetAll{{NAME}}Resource::collection($data);

                return $this->sendResponse(ResponseEnum::GET ,$response );

            }
            public  function  create(Create{{NAME}}Request $request){

                $data = $this->repository->create($request->validated());

                return $this->sendResponse(ResponseEnum::ADD ,$data );

            }
            public  function  update(Update{{NAME}}Request $request){

                $data = $this->repository->edit($request->{{lower_case_name}}_id , $request->validated());

                return $this->sendResponse(ResponseEnum::UPDATE ,$data );

            }
            public  function  delete(Delete{{NAME}}Request $request){

                $data = $this->repository->delete($request->id);

                return $this->sendResponse(ResponseEnum::DELETE ,$data );

            }

        }

        STUB;



        $NAMESPACE  = $namespace ? $namespace .DIRECTORY_SEPARATOR . $name : $name;
        // dd($NAMESPACE);
        $convertedString = str_replace('/', '\\', $NAMESPACE);

        $stub = str_replace('{{ControllerName}}', $controllerName, $content);
        $stub2 = str_replace('{{SERVICE}}', $service, $stub);
        $stub3 = str_replace('{{NAME}}', $name, $stub2);
        $stub4 = str_replace('{{NAMESPACE}}', $convertedString, $stub3);
        $stub5 = str_replace('{{lower_case_name}}', $lower_case_name_controller, $stub4);
        $stub6 = str_replace('{{NAMESPACE2}}', "\\" .$namespace, $stub5);


        file_put_contents($path, $stub6);

        // Create Function
        Artisan::call('make:custom-request', ['name' => "{$fill_path_with_last_name}/Create{$name}"]);


        // Update Function
        Artisan::call('make:custom-request', ['name' => "{$fill_path_with_last_name}/Update{$name}"]);


        // Delet Function
        Artisan::call('make:custom-request', ['name' => "{$fill_path_with_last_name}/Delete{$name}"]);


        // Get One Function
        Artisan::call('make:custom-request', ['name' => "{$fill_path_with_last_name}/GetOne{$name}"]);
        Artisan::call('make:resource', ['name' => "{$fill_path_with_last_name}/GetOne{$name}Resource"]);


        // Get ALl Function
        Artisan::call('make:custom-request', ['name' => "{$fill_path_with_last_name}/GetAll{$name}"]);
        Artisan::call('make:resource', ['name' => "{$fill_path_with_last_name}/GetAll{$name}Resource"]);
        Artisan::call('make:resource', ['name' => "{$fill_path_with_last_name}/GetAll{$name}Collection"]);


        $this->info('Controller created successfully!');
    }
}
