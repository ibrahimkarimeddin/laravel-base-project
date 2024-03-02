<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CopyToClipboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:custom-api {name}';

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

        $content = <<<EOT
        <?php


        Route::prefix('$name')->group(function(){
            Route::get('/getOne' , [YourController::class , 'getOne']);
            Route::get('/getAll' , [YourController::class , 'getAll']);
            Route::post('/add' , [YourController::class , 'add']);
            Route::post('/update' , [YourController::class , 'update']);
            Route::post('/delete' , [YourController::class , 'delete']);
        }); 
        EOT;

        $filePath = base_path("routes/api_to_copy.php");

        File::put($filePath, $content);

        $this->info("API routes for '$name' generated successfully!");
    }
}
