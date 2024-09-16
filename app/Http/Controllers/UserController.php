<?php

namespace App\Http\Controllers;

use App\Interfaces\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class UserController extends Controller
{
    protected UserInterface $userInterface;
    public function __construct(UserInterface $userInterface) {
        $this->userInterface = $userInterface;
    }
    public function signup(Request $request){
        return $this->userInterface->signup($request);
    }
    public function totalUsers(Request $request){
        return $this->userInterface->getEntireTableData($request);
    }

    public function callMicroServices(Request $request){
        $data=[];
        $data['microService']= $request->route()->parameter('serviceURL');
        $data['segment1']= $request->route()->parameter('segment1');
        $data['segment2']= $request->route()->parameter('segment2');
        $data['method']= $request->method();
        $data['url']= $request->path();
        return response()->json($data,200);
    }
}
