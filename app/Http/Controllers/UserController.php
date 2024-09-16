<?php

namespace App\Http\Controllers;

use App\Interfaces\UserInterface;
use Illuminate\Http\Request;


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
}
