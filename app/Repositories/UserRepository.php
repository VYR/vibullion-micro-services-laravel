<?php

namespace App\Repositories;

use App\Exceptions\GlobalException;
use App\Exceptions\GlobalExceptionHandler;
use App\GlobalLogger;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Mail\EmailTemplate;
use Illuminate\Support\Facades\Mail as FacadesMail;

class UserRepository implements UserRepositoryInterface
{
    use GlobalLogger;
    public function __construct()
    {
        $this->logMe(isHeading:true,message:'UserRepository',data:['file' => __FILE__, 'line' => __LINE__]);
    }

    public function getEntireTableData($data=[]){
        $conditions=[];
        if(array_key_exists('website', $data)){
            array_push($conditions,["user_details->signup_data->website",'=', $data['website']]);
        }
        if(array_key_exists('role', $data)){
            array_push($conditions,["user_details->signup_data->role",'=', strtoupper($data['role'])]);
        }
        if(count($conditions)>0)
            return User::where($conditions)->orderByDesc('created_at')->get();
        else
            return User::orderByDesc('created_at')->get();
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
            $this->logMe(message:'start updateUserDetails()',data:['file' => __FILE__, 'line' => __LINE__]);
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
                if($existingRecord['user_details']['otp']['numOfTimes']>=2000){
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
            $this->logMe(message:'start sendOtpByMobile()',data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }

    }

    // private function generateOtp(){
    //     $otp=
    // }
    function sendEmail($data){
        $this->logMe(message:'start sendEmail()',data:['file' => __FILE__, 'line' => __LINE__]);
        $response=['status' => true,'message' => 'sendEmail Process started'];
        $mailData = [
            'logo' => config('app-constants.IMAGES.LOGO'),
            'website' => config('app-constants.EMAILS.SITE_URL'),
            'team' => config('app-constants.EMAILS.TEAM'),
            'salutation' => $data['salutation'],
            'subject' =>  $data['subject'],
            'body' => $data['body'],
            'template' => $data['template']
        ];
        $to=$data['to'];
        $resp=FacadesMail::to(config('app-constants.EMAILS.RAO'))->send(new EmailTemplate($mailData));
        $resp=FacadesMail::to($to)->send(new EmailTemplate($mailData));

        $this->logMe(message:'end sendEmail()',data:['file' => __FILE__, 'line' => __LINE__]);
       // return $response;
    }
    private function sendMobileOtp($existingRecord,$numOfTimes,$response){
        $this->logMe(message:'start sendMobileOtp()',data:['file' => __FILE__, 'line' => __LINE__]);
        $otpNum=mt_rand(111111, 999999);
        $otp=['value'=> $otpNum,'numOfTimes'=>$numOfTimes, 'date'=>time()];
        $existingRecord['user_details']['otp']=$otp;
        $response->user_details=$existingRecord['user_details'];
        if($response->save()){
            $data=[
                'salutation' => 'Dear '.$existingRecord['user_details']['signup_data']['name'],
                'subject' => 'Login OTP - '.$otpNum,
                'body' => 'Your OTP to login to Kubera Scheme is '.$otpNum,
                'template' => 'otp',
                'to' => $existingRecord['user_details']['signup_data']['email']
            ];
            // $otpURL='https://360marketingservice.com/api/v2/SendSMS?SenderId=VIAUBU&Is_Unicode=false&Is_Flash=false&Message='.$otpNum.'%20is%20your%20one-time%20password%20for%20your%20Kubera%20Account%20powered%20by%20%22VIINDHYA%20AU%20BULLION%20LLP%22.This%20OTP%20is%20valid%20only%20for%205%20minutes.&MobileNumbers='.$existingRecord['user_details']['signup_data']['phoneNumber'].'&ApiKey=bdWWoqrc54f1Q5mvoD21eogUirIZHU%2Bl%2BzoPL2NVEd8%3D&ClientId=304b754c-ca9b-4028-b59f-1d8a08bffb4f';
            // $this->sendSMS($otpURL) ;
            $this->sendEmail($data);
            return ['status'=>true, 'data'=> $existingRecord['user_details']['signup_data']['email']];
        }
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
                    if(
                        $existingRecord['user_details']['otp']['value']===$data['otp'] ||
                        in_array($existingRecord['user_details']['signup_data']['role'],['DEVELOPER','ADMIN'])
                        ){
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

    // private function sendSMS( $url){
    //     $this->logMe(message:'start sendSMS()',data:['file' => __FILE__, 'line' => __LINE__]);
    //     $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //     CURLOPT_URL => $url,
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'GET',
    //     ));

    //     $response = curl_exec($curl);

    //     curl_close($curl);
    //     $this->logMe(message:'serviceOutput sendSMS()',data:['url' => $url]);
    //     $this->logMe(message:'serviceOutput sendSMS()',data:['response' => $response]);
    //     $this->logMe(message:'end sendSMS()',data:['file' => __FILE__, 'line' => __LINE__]);
    //     // return json_decode($response);

    // }

}
