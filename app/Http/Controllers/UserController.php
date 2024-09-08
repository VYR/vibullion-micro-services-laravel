<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function signup(Request $request){
        /* Create response data */
        $response=[
            'data' => [],
            'statusCode'=> 200
        ];

        $response['data']['input']=$request->all();
        $response['data']['headers']=$request->header('website');




        /*send response data */
        return response()->json($response['data'],$response['statusCode']);
    }
}
