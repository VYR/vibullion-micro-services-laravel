<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface UserInterface
{
    public function signup(Request $request);
    public function login(Request $request);
    public function updateUserDetails(Request $request);
    public function completeKyc(Request $request);
    public function sendOtpByMobile(Request $request);
    public function verifyOtp(Request $request);
    public function getEntireTableData(Request $request);
    public function handleMicroServices(Request $request);

}
