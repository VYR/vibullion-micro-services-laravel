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
        $data['email']=intval(implode(Arr::shuffle((str_split(time().random_int(1000000000, 9999999999))))));
        $data['password']=FacadesHash::make($request->password);
        $data['user_details']=['signup_data' => [
            ...$request->all(),
            'website'=> $request->header('website'),
            'userId'=> $data['email'],
        ]];
        $response['data']=$data;
        /** Call DB operations */
        $dbStatus=$this->userRepository->signup($data);
        $response['data']['dbStatus']=$dbStatus;
        if($dbStatus){
            $response['statusCode']=200;
            $response['msg']= config('app-constants.RESPONSE.MSG.SIGNUP.SUCCESS');
        }
        else {
            $response['statusCode']=404;
            $response['msg']= config('app-constants.RESPONSE.MSG.SIGNUP.FAILED');
        }
        $this->logMe(message:'end signup()',data:['file' => __FILE__, 'line' => __LINE__]);
        /*send response data */
       return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
    }
    public function login(Request $request)
    {
        $this->logMe(message:'start login()',data:['file' => __FILE__, 'line' => __LINE__]);
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
            $dbStatus=$this->userRepository->login($data);
            $response['data']=$dbStatus;
            $response['data']['request']=$data;
            // if($this->userRepository->login($data)){
            //     $response['statusCode']=200;
            //     $response['msg']= config('app-constants.RESPONSE.MSG.SIGNUP.SUCCESS');
            // }
            // else {
            //     $response['statusCode']=404;
            //     $response['msg']= config('app-constants.RESPONSE.MSG.SIGNUP.FAILED');
            // }
            $this->logMe(message:'end login()',data:['file' => __FILE__, 'line' => __LINE__]);
            $this->logMe(message:json_encode($dbStatus),data:['file' => __FILE__, 'line' => __LINE__]);
            /*send response data */
            return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }catch(\Exception $e){
            //return false;
            $this->logMe(message:'end login() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
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
        // try{
            $response['data']=$this->userRepository->getEntireTableData($request->all());
            $response['msg']=config('app-constants.RESPONSE.MSG.GET_DATA_SUCCESSFUL');
        // }catch(\Exception $e){
        //     return new GlobalExceptionHandler(data:$data);
        // }
        $this->logMe(message:'end getEntireTableData()',data:['file' => __FILE__, 'line' => __LINE__]);

        /*send response data */
        return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
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
        $serviceName=$request->route()->parameter('serviceName');
        $segment1=$request->route()->parameter('segment1');
        $segment2=$request->route()->parameter('segment2');

        $response['data']['microService']= $serviceName;
        $response['data']['segment1']= $segment1;
        $response['data']['segment2']= $segment2;
        $response['data']['method']= $requestMethod;
        $response['data']['MICRO_SERVICES']= $services;
        $response['data']['MICRO_SERVICES_SITE']= $services[$serviceName];
        $response['data']['IS_MICRO_SERVICES_AVAILABLE']= key_exists($serviceName,$services);
        $response['data']['url']= $request->path();
        $this->logMe(message:'end handleMicroServices()',data:['file' => __FILE__, 'line' => __LINE__]);

        return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }catch(\Exception $e){
            $this->logMe(message:'end handleMicroServices() at Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            return new GlobalException(data:$response);
        }
    }

    private function handleMicroServiceGetRequest(Request $request){

    }
}
