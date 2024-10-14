<?php

namespace App\Http\Traits;

trait ApiResponseTrait
{
    public function apiResponse($data= null , $status= null, $message = null ,$file = null)
    {
        $array = [
            'data' => $data,
            'message'=> $message,
            'status'=> $status,
        ];
        
        return response($array,$status);
    }
}
