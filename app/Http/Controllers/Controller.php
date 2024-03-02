<?php

namespace App\Http\Controllers;

use App\Enums\ResponseEnum;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($type , $data = [])
    {

        $message = ( $type == ResponseEnum::GET)  ?
        ('messages.get_data_successfully')
        : 
      ( $type ==  ResponseEnum::ADD ?
        ('messages.added_successfully') :  
     (   $type  == ResponseEnum::DELETE ?
        ('messages.deleted_successfully') : 
   (     $type == ResponseEnum::UPDATE?
        ('messages.updated_successfully') :
        $type) ));


        $response = [
            'success' => true,
            'data' => $data,
            'message' => __($message),
        ];

        return response()->json($response, 200);
    }

    public function sendError($message, $code = 400, $data = [])
    {
        $response = [
            'success' => false,
            'data' => $data,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }
}
