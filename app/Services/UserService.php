<?php

namespace App\Services;

use App\GlobalResponseData;
use App\Interfaces\UserInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

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
            'data' => '',
            'msg'=> '',
            'statusCode'=> 200
        ];

        $response['data']=$request->all();
        /** Call DB operations */
        if($this->userRepository->signup($request->all())){
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
