<?php

use App\Http\Controllers\Dashboard\User4Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('user')->group(function(){
    Route::get('/getOne' , [User4Controller::class , 'getOne']);
    Route::get('/getAll' , [User4Controller::class , 'getAll']);
    Route::post('/add' , [User4Controller::class , 'add']);
    Route::post('/update' , [User4Controller::class , 'update']);
    Route::post('/delete' , [User4Controller::class , 'delete']);
}); 