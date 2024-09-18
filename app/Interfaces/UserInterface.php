<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface UserInterface
{
    public function signup(Request $request);
    public function login(Request $request);
    public function getEntireTableData(Request $request);
    public function handleMicroServices(Request $request);
}
