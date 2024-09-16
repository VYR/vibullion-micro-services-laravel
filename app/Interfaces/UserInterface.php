<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface UserInterface
{
    public function signup(Request $request);
    public function getEntireTableData(Request $request);
}
