<?php

namespace App;

use Illuminate\Support\Str;
trait GlobalResponseData
{
    function sendResponse($errCode = 404, $errMsg='', $data='', $status='') {
        $this->errCode = $errCode;
        $this->errMsg = $errMsg;
        $this->data = $data;
        $this->status = $status;
        $data=[
            'message' =>  $this->errMsg,
            'data' =>  $this->data,
            'status' => Str::length($this->status)?$this->status:( $this->errCode===200?config('app-constants.BASIC.SUCCESS'):config('app-constants.BASIC.FAILED'))
        ];
        return response()->json($data, $this->errCode);
    }
}
