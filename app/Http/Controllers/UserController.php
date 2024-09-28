<?php

namespace App\Http\Controllers;

use App\Interfaces\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use App\GlobalLogger;

class UserController extends Controller
{
    use GlobalLogger;
    protected UserInterface $userInterface;
    public function __construct(UserInterface $userInterface) {
        $this->userInterface = $userInterface;
    }
    public function signup(Request $request){
        $this->logMe(message:'start signup() Controller',data:['file' => __FILE__, 'line' => __LINE__]);
        return $this->userInterface->signup($request);
    }
    public function totalUsers(Request $request){
        $this->logMe(message:'start totalUsers() Controller',data:['file' => __FILE__, 'line' => __LINE__]);
        return $this->userInterface->getEntireTableData($request);
    }
    public function getAadharUrl(Request $request){
        $this->logMe(message:'start getAadharUrl() Controller',data:['file' => __FILE__, 'line' => __LINE__]);
        return $this->userInterface->getAadharUrl($request);
    }
    public function updateBankDetails(Request $request){
        $this->logMe(message:'updateBankDetails()',data:['file' => __FILE__, 'line' => __LINE__]);
        return $this->userInterface->updateBankDetails($request);
    }
    public function updateDeliveryAddress(Request $request){
        $this->logMe(message:'updateDeliveryAddress()',data:['file' => __FILE__, 'line' => __LINE__]);
        return $this->userInterface->updateDeliveryAddress($request);
    }
    public function updatePaymentDetails(Request $request){
        $this->logMe(message:'updatePaymentDetails()',data:['file' => __FILE__, 'line' => __LINE__]);
        return $this->userInterface->updatePaymentDetails($request);
    }
    public function addPaymentDetails(Request $request){
        $this->logMe(message:'addPaymentDetails()',data:['file' => __FILE__, 'line' => __LINE__]);
        return $this->userInterface->addPaymentDetails($request);
    }

}
