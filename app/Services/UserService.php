<?php

namespace App\Services;

use App\Exceptions\GlobalException;
use App\GlobalLogger;
use App\GlobalResponseData;
use App\Interfaces\UserInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserService implements UserInterface
{
    use GlobalResponseData;
    use GlobalLogger;
    protected UserRepositoryInterface $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function signup(Request $request)
    {
         /* Create response data */
         $this->logMe(message:'start signup() Service',data:['file' => __FILE__, 'line' => __LINE__]);
        $response=[
            'data' => '',
            'msg'=> '',
            'statusCode'=> 200
        ];
        try{
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
            $this->logMe(message:'end signup() Service',data:['file' => __FILE__, 'line' => __LINE__]);
            return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }
        catch(\Exception $e){
            $this->logMe(message:'end singup() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:'', errMsg: $e->getMessage());
        }
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
    public function getAadharUrl(Request $request)
    {
        $this->logMe(message:'start getAadharUrl() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
         /* Create response data */
         $response=[
            'data' => [],
            'msg' => '',
            'statusCode'=> 200
        ];
        $data=$request->all();
        try{
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://live.meon.co.in/get_sso_route',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
                "company" : "viindhya",
                "workflowName" : "kuberascheme",
                "secret_key" : "k9txD5nWtwS6e38iiwDqL26Vb0vVi2iq",
                "notification" : true,
                "unique_keys" : {"referenceno" : '.$request->referenceno.'}
            }',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                 ),
            ));

            $res = curl_exec($curl);

            curl_close($curl);
            $response['data']=  json_decode($res);

             /*send response data */
             return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
            }catch(\Exception $e){
                $this->logMe(message:'end getAadharUrl() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
                throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
            }
    }
    public function updateBankDetails(Request $request)
    {
        $this->logMe(message:'start updateBankDetails() Service',data:['file' => __FILE__, 'line' => __LINE__]);
        $response=[
            'data' => [],
            'msg'=> '',
            'statusCode'=> 200
        ];
        $data=$request->all();
        // $data['website']= $request->header('website');
        try{
            $dbStatus=$this->userRepository->updateBankDetails($data);
            if($dbStatus['status']){
                $response['statusCode']=200;
                $response['msg']= $dbStatus['msg'];
            }
            else {
                $response['statusCode']=404;
                $response['msg']= $dbStatus['msg'];
            }
            $this->logMe(message:'end updateBankDetails() Service',data:['file' => __FILE__, 'line' => __LINE__]);
            $this->logMe(message:json_encode($dbStatus),data:['file' => __FILE__, 'line' => __LINE__]);
            return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }catch(\Exception $e){
            $this->logMe(message:'end updateBankDetails() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            $this->logMe(message: $e->getMessage(),data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }
    public function updateDeliveryAddress(Request $request)
    {
        $this->logMe(message:'start updateDeliveryAddress() Service',data:['file' => __FILE__, 'line' => __LINE__]);
        $response=[
            'data' => [],
            'msg'=> '',
            'statusCode'=> 200
        ];
        $data=$request->all();
        // $data['website']= $request->header('website');
        try{
            $dbStatus=$this->userRepository->updateDeliveryAddress($data);
            if($dbStatus['status']){
                $response['statusCode']=200;
                $response['msg']= $dbStatus['msg'];
            }
            else {
                $response['statusCode']=404;
                $response['msg']= $dbStatus['msg'];
            }
            $this->logMe(message:'end updateDeliveryAddress() Service',data:['file' => __FILE__, 'line' => __LINE__]);
            $this->logMe(message:json_encode($dbStatus),data:['file' => __FILE__, 'line' => __LINE__]);
            return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }catch(\Exception $e){
            $this->logMe(message:'end updateDeliveryAddress() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            $this->logMe(message: $e->getMessage(),data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }
    public function addContactMessages(Request $request)
    {
         /* Create response data */
         $this->logMe(message:'start addContactMessages() Service',data:['file' => __FILE__, 'line' => __LINE__]);
        $response=[
            'data' => '',
            'msg'=> '',
            'statusCode'=> 200
        ];
        try{
            $response['data']=$request->all();
            /** Call DB operations */
            if($this->userRepository->addContactMessages($request->all())){
                $response['statusCode']=200;
                $response['msg']= 'Your details received successfully. Our team will contact you soon';
            }
            else {
                $response['statusCode']=404;
                $response['msg']= 'Something went wrong please try again';
            }
            /*send response data */
            $this->logMe(message:'end addContactMessages() Service',data:['file' => __FILE__, 'line' => __LINE__]);
            return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }
        catch(\Exception $e){
            $this->logMe(message:'end addContactMessages() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:'', errMsg: $e->getMessage());
        }
    }
    public function updateContactMessages(Request $request)
    {
        $this->logMe(message:'start updateContactMessages() Service',data:['file' => __FILE__, 'line' => __LINE__]);
        $response=[
            'data' => [],
            'msg'=> '',
            'statusCode'=> 200
        ];
        $data=$request->all();
        // $data['website']= $request->header('website');
        try{
            $dbStatus=$this->userRepository->updateContactMessages($data);
            if($dbStatus['status']){
                $response['statusCode']=200;
                $response['msg']= $dbStatus['msg'];
            }
            else {
                $response['statusCode']=404;
                $response['msg']= $dbStatus['msg'];
            }
            $this->logMe(message:'end updateContactMessages() Service',data:['file' => __FILE__, 'line' => __LINE__]);
            $this->logMe(message:json_encode($dbStatus),data:['file' => __FILE__, 'line' => __LINE__]);
            return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
        }catch(\Exception $e){
            $this->logMe(message:'end updateContactMessages() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
            $this->logMe(message: $e->getMessage(),data:['file' => __FILE__, 'line' => __LINE__]);
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }
    }
}
