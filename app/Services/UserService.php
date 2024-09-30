<?php

namespace App\Services;

use App\Exceptions\GlobalException;
use App\GlobalLogger;
use App\GlobalResponseData;
use App\Interfaces\UserInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserService implements UserInterface
{
    use GlobalResponseData;
    use GlobalLogger;
    protected UserRepositoryInterface $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->logMe(isHeading:true,message:'UserService',data:['file' => __FILE__, 'line' => __LINE__]);
        $this->userRepository = $userRepository;
    }

    public function signup(Request $request)
    {
        $this->logMe(message:'start signup()',data:['file' => __FILE__, 'line' => __LINE__]);
         /* Create response data */
         $response=[
            'data' => [],
            'msg'=> '',
            'statusCode'=> ''
        ];
        /** Prepare model or table or DB Data */
        $data=[];
        try{
            $data['email']=time().random_int(1000000, 9999999);
            $data['password']=FacadesHash::make($request->password);
            $data['user_details']=['signup_data' => [
                ...$request->all(),
                'website'=> $request->header('website'),
                'userId'=> $data['email'],
            ]];
            /**Call micro service */
            $services=config('app-constants.MICRO_SERVICES');
            $serviceName=$services['WEBSITES'][$request->header('website')];
            $prepareUrl= $services[$serviceName]['URL'].$services[$serviceName]['SIGNUP'];
            // $response['data']=$data;
            /** Call DB operations */
            $dbStatus=$this->userRepository->signup($data);
            // $response['data']['dbStatus']=$dbStatus;
            if($dbStatus['status']){
                $params=[
                    'id' => $data['email'],
                    'user_details' => $data['user_details']
                ];
                $resp=$this->handleMicroServicePostRequest($prepareUrl, $params);
                $response['data']['mic_signup']=$resp;
                $response['data']['mic_signup_type']=is_array($resp);
                $response['data']['mic_signup_type']=$resp->status;
                $response['statusCode']=200;
                $response['msg']= config('app-constants.RESPONSE.MSG.SIGNUP.SUCCESS');
            }
            else {
                $response['statusCode']=404;
                $response['msg']= $dbStatus['data'];
                // Get the currently authenticated user...
                $user = Auth::user();
                $response['data']['dbStatus']=$user;
                // Get the currently authenticated user's ID...
                // $id = Auth::id();
            }
            $this->logMe(message:'end signup()',data:['file' => __FILE__, 'line' => __LINE__]);
            /*send response data */
            return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }
        catch(\Exception $e){
            //return false;
            $this->logMe(message:'end signup() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:[], errMsg: $e->getMessage());
        }
    }
    public function login(Request $request)
    {
        $this->logMe(message:'start login()',data:['file' => __FILE__, 'line' => __LINE__]);
        $response=[
            'data' => [],
            'msg'=> '',
            'statusCode'=> 200
        ];
        $data=$request->all();
        $data['website']= $request->header('website');
        try{
            $dbStatus=$this->userRepository->login($data);
            if($dbStatus){
                $response['statusCode']=200;
                $response['msg']= config('app-constants.RESPONSE.MSG.LOGIN.SUCCESS');
                $a=$dbStatus['user']->toArray();
                unset($a['user_details']['signup_data']['password']);
                $dbStatus['user']=$a;
                $response['data']=$dbStatus;

            }
            else {
                $response['statusCode']=404;
                $response['msg']= config('app-constants.RESPONSE.MSG.LOGIN.FAILED');
                $response['data']=[];
            }
            $this->logMe(message:'end login()',data:['file' => __FILE__, 'line' => __LINE__]);
            $this->logMe(message:json_encode($dbStatus),data:['file' => __FILE__, 'line' => __LINE__]);
            return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }catch(\Exception $e){
            $this->logMe(message:'end login() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            $this->logMe(message: $e->getMessage(),data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }

    public function updateUserDetails(Request $request)
    {
        $this->logMe(message:'start updateUserDetails()',data:['file' => __FILE__, 'line' => __LINE__]);
        $response=[
            'data' => [],
            'msg'=> '',
            'statusCode'=> 200
        ];
        $data=$request->all();
        // $data['website']= $request->header('website');
        try{
            $dbStatus=$this->userRepository->updateUserDetails($data);
            if($dbStatus['status']){
                $response['statusCode']=200;
                $response['msg']= $dbStatus['msg'];
            }
            else {
                $response['statusCode']=404;
                $response['msg']= $dbStatus['msg'];
            }
            $this->logMe(message:'end updateUserDetails()',data:['file' => __FILE__, 'line' => __LINE__]);
            $this->logMe(message:json_encode($dbStatus),data:['file' => __FILE__, 'line' => __LINE__]);
            return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }catch(\Exception $e){
            $this->logMe(message:'end updateUserDetails() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            $this->logMe(message: $e->getMessage(),data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }

    public function getEntireTableData(Request $request)
    {
        $this->logMe(message:'start getEntireTableData()',data:['file' => __FILE__, 'line' => __LINE__]);
         /* Create response data */
         $response=[
            'data' => [],
            'statusCode'=> 200
        ];
        $data=$request->all();
        if($request->hasHeader('website'))
        $data['website']= $request->header('website');
        try{
            $dbStatus=$this->userRepository->getEntireTableData($data);
            $response['data']=['content' => $dbStatus,'totalRecords' => count($dbStatus)];
            $response['msg']=config('app-constants.RESPONSE.MSG.GET_DATA_SUCCESSFUL');
            $this->logMe(message:'end getEntireTableData()',data:['file' => __FILE__, 'line' => __LINE__]);
            /*send response data */
            return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }
        catch(\Exception $e){
            $this->logMe(message:'end getEntireTableData() at Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(data:$response, errMsg: $e->getMessage());
        }
    }

    public function handleMicroServices(Request $request){
        $this->logMe(message:'start handleMicroServices()',data:['file' => __FILE__, 'line' => __LINE__]);
           /* Create response data */
        $response=[
            'data' => [],
            'msg' => '',
            'statusCode'=> 200
        ];
        try{
        $services=config('app-constants.MICRO_SERVICES');
        $requestMethod=$request->method();
        $this->logMe(message:'Invalid Method',data:['method' => $requestMethod, 'line' => __LINE__]);
        $serviceName=$request->route()->parameter('serviceName');
        $segment1=$request->route()->parameter('segment1');
        $segment2=$request->route()->parameter('segment2');
        if(!key_exists($serviceName,$services)){
            $response['msg']='Invalid Micro Service';
            $response['statusCode']=404;
            return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }
        $response['data']['microService']= $serviceName;
        $response['data']['method']= $requestMethod;
        $prepareUrl= $services[$serviceName]['URL'];
        if($segment1){
            $prepareUrl=$prepareUrl.$segment1;
        }
        if($segment2){
            $prepareUrl=$prepareUrl.'/'.$segment2;
        }
        $params=$request->all();
        if($requestMethod==='GET'){
            if(count($params)>0){
                $prepareUrl = $prepareUrl . '?' . http_build_query($params);
            }
            return $this->handleMicroServiceGetRequest($prepareUrl);
        }
        else if($requestMethod==='POST'){
            if(count($params)>0){
                return $this->handleMicroServicePostRequest($prepareUrl, $params);
            }
            else{
                $response['msg']='Post Request must have Data';
                $response['statusCode']=404;
                $this->logMe(message:'Post Request must have Data',data:['file' => __FILE__, 'line' => __LINE__]);
                return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
            }
        }
        else{
            $response['msg']='Invalid Method';
            $response['statusCode']=404;
            $this->logMe(message:'Invalid Method',data:['file' => __FILE__, 'line' => __LINE__]);
            return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }

        }
        catch(\Exception $e){
            $this->logMe(message:'end handleMicroServices() at Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(data:$response, errMsg: $e->getMessage());
        }
    }


    private function handleMicroServiceGetRequest( $url){
        $this->logMe(message:'start handleMicroServiceGetRequest()',data:['file' => __FILE__, 'line' => __LINE__]);
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $this->logMe(message:'serviceOutput handleMicroServiceGetRequest()',data:['url' => $url]);
        $this->logMe(message:'serviceOutput handleMicroServiceGetRequest()',data:['response' => $response]);
        $this->logMe(message:'end handleMicroServiceGetRequest()',data:['file' => __FILE__, 'line' => __LINE__]);
        return json_decode($response);

    }

    private function handleMicroServicePostRequest( $url, $data){

        $this->logMe(message:'start handleMicroServicePostRequest()',data:['file' => __FILE__, 'line' => __LINE__]);
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $this->logMe(message:'serviceOutput handleMicroServicePostRequest()',data:['url' => $url]);
        $this->logMe(message:'serviceOutput handleMicroServicePostRequest()',data:['response' => $response]);
        $this->logMe(message:'end handleMicroServicePostRequest()',data:['file' => __FILE__, 'line' => __LINE__]);
        return json_decode($response);

    }

    public function sendOtpByMobile(Request $request)
    {
        $this->logMe(message:'start sendOtpByMobile()',data:['file' => __FILE__, 'line' => __LINE__]);
        $response=[
            'data' => [],
            'msg'=> '',
            'statusCode'=> 200
        ];
        /** Prepare model or table or DB Data */
        $data=$request->all();
        $data['website']= $request->header('website');
        try{
            /* Create response data */


            /** Call DB operations */
            $dbStatus=null;
            //$response['data']= $data;
            if(array_key_exists('email', $data) && array_key_exists('mobile', $data))  {
                $response['statusCode']=404;
                $response['msg']= 'Either Email or Mobile are accepted';
            }
            else if(array_key_exists('email', $data)){
                $response['msg']= 'OTP Sent Successfully to your Email Id';
            }
            else{
                $dbStatus=$this->userRepository->sendOtpByMobile($data);
                $this->logMe(message:'start sendOtpByMobile()',data:['file' => $dbStatus]);
                if($dbStatus['status']){
                    $response['data']= $dbStatus['data'];
                    $response['msg']= 'OTP Sent Successfully to your Mobile Number';
                }
                else{
                    $response['msg']= $dbStatus['data'];
                    $response['statusCode']=404;
                }
            }
            $this->logMe(message:'end sendOtpByMobile()',data:['file' => __FILE__, 'line' => __LINE__]);
            /*send response data */
            return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }catch(\Exception $e){
            //return false;
            $this->logMe(message:'end sendOtpByMobile() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            $this->logMe(message: $e->getMessage(),data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }

    public function verifyOtp(Request $request)
    {
        $this->logMe(message:'start verifyOtp()',data:['file' => __FILE__, 'line' => __LINE__]);
        $response=[
            'data' => [],
            'msg'=> '',
            'statusCode'=> 200
        ];
        $data=$request->all();
        $data['website']= $request->header('website');
        try{
            $dbStatus=$this->userRepository->verifyOtp($data);
            if($dbStatus){
                $response['statusCode']=200;
                $response['msg']= 'OTP Verified Successfully';
                $a=$dbStatus['user']->toArray();
                unset($a['user_details']['signup_data']['password']);
                unset($a['user_details']['otp']);
                $a['email']=strval($a['email']);
                $dbStatus['user']=$a;
                $response['data']=$dbStatus;
            }
            else {
                $response['statusCode']=404;
                $response['msg']='Invalid OTP. Please try again';
                $response['data']=[];
            }
            $this->logMe(message:'end verifyOtp()',data:['file' => __FILE__, 'line' => __LINE__]);
            $this->logMe(message:json_encode($dbStatus),data:['file' => __FILE__, 'line' => __LINE__]);
            return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }catch(\Exception $e){
            $this->logMe(message:'end verifyOtp() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            $this->logMe(message: $e->getMessage(),data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }
}
