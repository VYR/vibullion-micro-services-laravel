<?php

namespace App\Repositories;
use App\Models\User;
use App\Models\Payment;
use App\Exceptions\GlobalException;
use App\GlobalLogger;
use App\RepositoryInterfaces\PaymentRepositoryInterface;

class PaymentRepository implements PaymentRepositoryInterface
{
    use GlobalLogger;
    public function __construct()
    {
        //
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
                $response->bank_details=$data['payment_details'];
                if ($response->save()) {
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
                        'msg'=> " Payment Completed Successfully",
                        'status' => true
                    ];
                }
                else{
                    return [
                        'msg'=> "Unable to Complete Payment",
                        'status' => false
                    ];
                }
            }

        }catch(\Exception $e){
            $this->logMe(message:'end addPaymentDetails() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }
    public function getPaymentsByUser(array $data){
        $this->logMe(message:'start getPaymentsByUser() Repository',data:['file' => __FILE__, 'line' => __LINE__]);
        try{
            if(!array_key_exists('userId', $data)){
                return [
                    'msg'=> " User Id key is mandatory",
                    'status' => false
                ];
            }

            $conditions=[
                ["userId",'=', $data['userId']]
            ];
            return [
                'data' => Payment::where($conditions)->orderByDesc('created_at')->get(),
                'msg'=> "Your payment details fetched successfully",
                'status' => true
            ];

        }catch(\Exception $e){
            $this->logMe(message:'end getPaymentsByUser() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }
    public function getAllPayments(array $data){
        $this->logMe(message:'start getAllPayments() Repository',data:['file' => __FILE__, 'line' => __LINE__]);
        try{
            return Payment::orderByDesc('created_at')->get();
        }catch(\Exception $e){
            $this->logMe(message:'end getAllPayments() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }



}
