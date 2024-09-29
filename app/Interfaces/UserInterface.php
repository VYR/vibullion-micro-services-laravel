<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface UserInterface
{
    public function signup(Request $request);
    public function getEntireTableData(Request $request);
    public function getAadharUrl(Request $request);
    public function updateBankDetails(Request $request);
    public function updateDeliveryAddress(Request $request);



}
