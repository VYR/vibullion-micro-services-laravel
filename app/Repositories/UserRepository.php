<?php

namespace App\Repositories;

use App\Exceptions\GlobalException;
use App\Exceptions\GlobalExceptionHandler;
use App\GlobalLogger;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserRepository implements UserRepositoryInterface
{
    use GlobalLogger;
    public function __construct()
    {
        $this->logMe(isHeading:true,message:'UserRepository',data:['file' => __FILE__, 'line' => __LINE__]);
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
        $resp=['status'=>false, 'data'=>''];
        $this->logMe(message:'start signup()',data:['file' => __FILE__, 'line' => __LINE__]);
        try{
            $conditions=[];
            if(array_key_exists('email', $data['user_details']['signup_data'])){
                $conditions=[
                    ["user_details->signup_data->email",'=', $data['user_details']['signup_data']['email']],
                    ["user_details->signup_data->website",'=', $data['user_details']['signup_data']['website']]
                ];
                $response=User::where($conditions)->first();
                if(!is_null($response)){
                    $resp['data']='Email Already Existed';
                    return $resp;
                }
            }
            if(array_key_exists('phoneNumber', $data['user_details']['signup_data'])){
                $conditions=[
                    ["user_details->signup_data->phoneNumber",'=', $data['user_details']['signup_data']['phoneNumber']],
                    ["user_details->signup_data->website",'=', $data['user_details']['signup_data']['website']]
                ];
                $response=User::where($conditions)->first();
                if(!is_null($response)){
                    $resp['data']='Phone Number ALready Existed';
                    return $resp;
                }
            }

            $user=new User();
            $user->fill($data);
            $user->user_details=$data['user_details'];
            $resp['status']=$user->save();
            return $resp;

        }
        catch(\Exception $e){
            throw new GlobalException(errCode:404,data:[], errMsg: $e->getMessage());
        }
    }
    public function login(array $data){
        $this->logMe(message:'start login()',data:['file' => __FILE__, 'line' => __LINE__]);
        try{
            $conditions1=[
                ["user_details->signup_data->email",'=', $data['email']],
                ["user_details->signup_data->password",'=', $data['password']],
                ["user_details->signup_data->website",'=', $data['website']]
            ];
            $conditions2=[
                ["user_details->signup_data->phoneNumber",'=', $data['email']],
                ["user_details->signup_data->password",'=', $data['password']],
                ["user_details->signup_data->website",'=', $data['website']]
            ];
            $response=User::where($conditions1)->orWhere($conditions2)->first();
            if(is_null($response)){
                return $response;
            }
            else{
                return [
                    'user'=> $response,
                    'token' => $response->createToken($response->email)->plainTextToken
                ];
            }
        }catch(\Exception $e){
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }

    public function updateUserDetails(array $data){
        $this->logMe(message:'start updateUserDetails()',data:['file' => __FILE__, 'line' => __LINE__]);
        try{
            if(!array_key_exists('userId', $data)){
                return [
                    'msg'=> " User Id key is mandatory",
                    'status' => false
                ];
            }
            $conditions=[
                ["email",'=', $data['userId']]
            ];
            $response=User::where($conditions)->first();
            if(is_null($response)){
                return [
                    'msg'=> "Invalid User",
                    'status' => false
                ];
            }
            else{
                $existingRecord=$response->toArray();
                foreach ($data as $key => $value ) {
                    if(array_key_exists($key, $existingRecord['user_details']['signup_data'])){
                        $existingRecord['user_details']['signup_data'][$key]=$value;
                    }
                }
                $response->user_details=$existingRecord['user_details'];
                if ($response->save()) {
                    return [
                        'msg'=> " User Updated Successfully",
                        'status' => true
                    ];
                }
                else{
                    return [
                        'msg'=> "Unable to Update user",
                        'status' => false
                    ];
                }
            }
        }catch(\Exception $e){
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }

    public function updateBankDetails(array $data){
        $this->logMe(message:'start updateUserDetails()',data:['file' => __FILE__, 'line' => __LINE__]);
        try{
            if(!array_key_exists('userId', $data)){
                return [
                    'msg'=> " User Id key is mandatory",
                    'status' => false
                ];
            }
            $conditions=[
                ["email",'=', $data['userId']]
            ];
            $response=User::where($conditions)->first();
            if(is_null($response)){
                return [
                    'msg'=> "Invalid User",
                    'status' => false
                ];
            }
            else{
                $existingRecord=$response->toArray();
                foreach ($data as $key => $value ) {
                    if(array_key_exists($key, $existingRecord['user_details']['signup_data'])){
                        $existingRecord['user_details']['signup_data'][$key]=$value;
                    }
                }
                $response->user_details=$existingRecord['user_details'];
                if ($response->save()) {
                    return [
                        'msg'=> " User Updated Successfully",
                        'status' => true
                    ];
                }
                else{
                    return [
                        'msg'=> "Unable to Update user",
                        'status' => false
                    ];
                }
            }
        }catch(\Exception $e){
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }


    public function sendOtpByMobile(array $data){
        $this->logMe(message:'start sendOtpByMobile()',data:['file' => __FILE__, 'line' => __LINE__]);
        try{
            $conditions=[
                ["user_details->signup_data->phoneNumber",'=', $data['mobile']],
                ["user_details->signup_data->website",'=', $data['website']]
            ];
            $response=User::where($conditions)->first();
            $existingRecord=[];
            if($response)
                $existingRecord=$response->toArray();
            if(is_null($response)){
                return ['status' => false, 'data' => 'Invalid mobile number. Please try again with another number'];
            }
            else if(array_key_exists('otp', $existingRecord['user_details'])){
                $to_time = time();
                $from_time = $existingRecord['user_details']['otp']['date'];
                $timeDifference=round(abs($to_time - $from_time) / 60,2);
                if($existingRecord['user_details']['otp']['numOfTimes']>=2){
                    if($timeDifference<5){
                        return ['status'=>false, 'data'=>'Your OTP request limit 3 times exceeded.'];
                    }
                    else{
                        return $this->sendMobileOtp($existingRecord,0,$response);
                    }
                }
                else{
                    return $this->sendMobileOtp($existingRecord,($existingRecord['user_details']['otp']['numOfTimes']+1),$response);
                }
            }
            else{
                return $this->sendMobileOtp($existingRecord,0,$response);

            }

        }catch(\Exception $e){
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }

    }

    // private function generateOtp(){
    //     $otp=
    // }

    private function sendMobileOtp($existingRecord,$numOfTimes,$response){
        $otp=['value'=>mt_rand(111111, 999999),'numOfTimes'=>$numOfTimes, 'date'=>time()];
        $existingRecord['user_details']['otp']=$otp;
        $response->user_details=$existingRecord['user_details'];
        if($response->save())
            return ['status'=>true, 'data'=>''];
        else
            return ['status'=>false, 'data'=>''];
    }

    public function verifyOtp(array $data){
        $this->logMe(message:'start verifyOtp()',data:['file' => __FILE__, 'line' => __LINE__]);
        try{
            $conditions1=[
                ["user_details->signup_data->email",'=', $data['email']],
                ["user_details->signup_data->website",'=', $data['website']]
            ];
            $conditions2=[
                ["user_details->signup_data->phoneNumber",'=', $data['email']],
                ["user_details->signup_data->website",'=', $data['website']]
            ];
            $response=User::where($conditions1)->orWhere($conditions2)->first();
            if(!array_key_exists('otp', $data)){
                return null;
            }
            if(is_null($response)){
                return $response;
            }
            else{
                $existingRecord=$response->toArray();
                if(array_key_exists('otp', $existingRecord['user_details'])){
                    if($existingRecord['user_details']['otp']['value']===$data['otp']){
                        return [
                            'user'=> $response,
                            'token' => $response->createToken($response->email)->plainTextToken
                        ];
                    }
                    else
                        return null;
                }
                else
                    return null;
            }
        }catch(\Exception $e){
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }

}
