<?php

namespace App\Repositories;
use App\Exceptions\GlobalExceptionHandler;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Arr;
use App\Exceptions\GlobalException;
use App\GlobalLogger;

class UserRepository implements UserRepositoryInterface
{
    use GlobalLogger;
    public function __construct()
    {
        //
    }

    public function getEntireTableData($data=[]){
        return User::all();
    }
    public function findById($id){

    }
    public function findByEmail($email){

    }
    public function findByUsername($username){

    }
    public function findByEmailAndPassword($email, $password){

    }
    public function createUserByEmail(array $data){

    }
    public function signup(array $data){
        $this->logMe(message:'start signup() Repository',data:['file' => __FILE__, 'line' => __LINE__]);
        try{
            $user=new User();
            $user->id=$data['id'];
            $user->user_details=$data['user_details'];
            $this->logMe(message:'end signup() Repository',data:['file' => __FILE__, 'line' => __LINE__]);
            return $user->save();
        }
        catch(\Exception $e){
            $this->logMe(message:'end singup() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }
    public function updateBankDetails(array $data){
        $this->logMe(message:'start updateBankDetails() Repository',data:['file' => __FILE__, 'line' => __LINE__]);
        try{
            if(!array_key_exists('userId', $data)){
                return [
                    'msg'=> " User Id key is mandatory",
                    'status' => false
                ];
            }
            if(!array_key_exists('bank_details', $data)){
                return [
                    'msg'=> " Bank details key is mandatory",
                    'status' => false
                ];
            }
            $conditions=[
                ["id",'=', $data['userId']]
            ];
            $response=User::where($conditions)->first();
            if(is_null($response)){
                return [
                    'msg'=> "Invalid User",
                    'status' => false
                ];
            }
            else{
                $response->bank_details=$data['bank_details'];
                if ($response->save()) {
                    return [
                        'msg'=> " Bank Details Updated Successfully",
                        'status' => true
                    ];
                }
                else{
                    return [
                        'msg'=> "Unable to Update Bank Details",
                        'status' => false
                    ];
                }
            }
            $this->logMe(message:'end updateBankDetails() Repository',data:['file' => __FILE__, 'line' => __LINE__]);
        }catch(\Exception $e){
            $this->logMe(message:'end updateBankDetails() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }
    public function updateDeliveryAddress(array $data){
        $this->logMe(message:'start updateDeliveryAddress() Repository',data:['file' => __FILE__, 'line' => __LINE__]);
        try{
            if(!array_key_exists('userId', $data)){
                return [
                    'msg'=> " User Id key is mandatory",
                    'status' => false
                ];
            }
            if(!array_key_exists('delivery_address', $data)){
                return [
                    'msg'=> " Delivery Address key is mandatory",
                    'status' => false
                ];
            }
            $conditions=[
                ["id",'=', $data['userId']]
            ];
            $response=User::where($conditions)->first();
            if(is_null($response)){
                return [
                    'msg'=> "Invalid User",
                    'status' => false
                ];
            }
            else{
                $response->delivery_address=$data['delivery_address'];
                if ($response->save()) {
                    return [
                        'msg'=> " Delivery Address Updated Successfully",
                        'status' => true
                    ];
                }
                else{
                    return [
                        'msg'=> "Unable to Update Delivery Address",
                        'status' => false
                    ];
                }
            }
            $this->logMe(message:'end updateDeliveryAddress() Repository',data:['file' => __FILE__, 'line' => __LINE__]);
        }catch(\Exception $e){
            $this->logMe(message:'end updateDeliveryAddress() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());

        }
    }
    public function updatePaymentDetails(array $data){
        $this->logMe(message:'start updatePaymentDetails() Repository',data:['file' => __FILE__, 'line' => __LINE__]);
        try{
            if(!array_key_exists('userId', $data)){
                return [
                    'msg'=> " User Id key is mandatory",
                    'status' => false
                ];
            }
            if(!array_key_exists('payment_details', $data)){
                return [
                    'msg'=> " Payment details key is mandatory",
                    'status' => false
                ];
            }
            $conditions=[
                ["id",'=', $data['userId']]
            ];
            $response=User::where($conditions)->first();
            if(is_null($response)){
                return [
                    'msg'=> "Invalid User",
                    'status' => false
                ];
            }
            else{
                $payment=new Payment();
                $payment->userId=$data['userId'];
                $payment->payment_details=$data['payment_details'];
                if ($payment->save()) {
                    return [
                        'msg'=> " Payment Details Updated Successfully",
                        'status' => true
                    ];
                }
                else{
                    return [
                        'msg'=> "Unable to Update Payment Details",
                        'status' => false
                    ];
                }
            }
            $this->logMe(message:'end updatePaymentDetails() Repository',data:['file' => __FILE__, 'line' => __LINE__]);
        }catch(\Exception $e){
            $this->logMe(message:'end updatePaymentDetails() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }
    public function addPaymentDetails(array $data){
        $this->logMe(message:'start addPaymentDetails() Repository',data:['file' => __FILE__, 'line' => __LINE__]);
        try{
            if(!array_key_exists('userId', $data)){
                return [
                    'msg'=> " User Id key is mandatory",
                    'status' => false
                ];
            }
            if(!array_key_exists('payment_details', $data)){
                return [
                    'msg'=> " Payment details key is mandatory",
                    'status' => false
                ];
            }
            $conditions=[
                ["id",'=', $data['userId']]
            ];
            $response=User::where($conditions)->first();
            if(is_null($response)){
                return [
                    'msg'=> "Invalid User",
                    'status' => false
                ];
            }
            else{
                $payment=new Payment();
                $payment->userId=$data['userId'];
                $payment->payment_details=$data['payment_details'];
                if ($payment->save()) {
                    return [
                        'msg'=> " Payment Details Updated Successfully",
                        'status' => true
                    ];
                }
                else{
                    return [
                        'msg'=> "Unable to Update Payment Details",
                        'status' => false
                    ];
                }
            }
            $this->logMe(message:'end addPaymentDetails() Repository',data:['file' => __FILE__, 'line' => __LINE__]);
        }catch(\Exception $e){
            $this->logMe(message:'end addPaymentDetails() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }



}
