<?php

namespace App\Services;

use App\Exceptions\GlobalExceptionHandler;
use App\GlobalResponseData;
use App\Interfaces\UserInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Throwable;

class UserService implements UserInterface
{
    use GlobalResponseData;
    protected UserRepositoryInterface $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function signup(Request $request)
    {
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
        if($this->userRepository->signup($data)){
            $response['statusCode']=200;
            $response['msg']= config('app-constants.RESPONSE.MSG.SIGNUP.SUCCESS');
        }
        else {
            $response['statusCode']=404;
            $response['msg']= config('app-constants.RESPONSE.MSG.SIGNUP.FAILED');
        }
        /*send response data */
       return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
    }
    public function getEntireTableData(Request $request)
    {
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
        /*send response data */
        return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
    }

}
