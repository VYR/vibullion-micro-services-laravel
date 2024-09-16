<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Str;
use Throwable;

class GlobalException extends Exception
{
    public $errCode;
    public $errMsg;
    public $data;
    public $status;
    public function __construct($errCode = 404, $errMsg='', $data='', $status='',$request=[]) {
        $this->errCode = $errCode;
        $this->errMsg = $errMsg;
        $this->data = $data;
        $this->status = $status;
        $this->render($request);
    }

    function render($request)
    {
        return new GlobalExceptionHandler($this->errCode,$this->errMsg,$this->data,$this->status);
    }

}

