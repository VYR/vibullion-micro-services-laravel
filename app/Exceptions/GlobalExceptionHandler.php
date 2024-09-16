<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Str;
use Throwable;

class GlobalExceptionHandler implements ExceptionHandler
{
    public $errCode;
    public $errMsg;
    public $data;
    public $status;
    public function __construct($errCode = 404, $errMsg='', $data='', $status='') {
        $this->errCode = $errCode;
        $this->errMsg = $errMsg;
        $this->data = $data;
        $this->status = $status;
    }
    function shouldReport(Throwable $e){

    }
    function report(Throwable $e){

        //return false;
    }

    function render($request, Throwable $e)
    {
        if (!$request->is('api/*')) {
                return response()->json([
                    'message' => 'Invalid Request'
                ], 404);
        }
        if(!strlen($this->errMsg)) {
            $this->errMsg= $e->getMessage();
        }
        $data=[
            'message' =>  $this->errMsg,
            'data' =>  $this->data,
            'errCode' =>  $this->errCode,
            'status4' => Str::length($this->status)?$this->status:( $this->errCode===200?config('app-constants.BASIC.SUCCESS'):config('app-constants.BASIC.FAILED'))
        ];
        return response()->json($data, $this->errCode);
    }

    function renderForConsole($output, Throwable $e)
    {
        return response()->json([]);
    }

}
