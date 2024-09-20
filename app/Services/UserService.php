<?php

namespace App\Services;

use App\Exceptions\GlobalException;
use App\GlobalLogger;
use App\GlobalResponseData;
use App\Interfaces\UserInterface;
use App\Interfaces\UserRepositoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Request as GuzzleHttp;
use Psr\Http\Message\ResponseInterface;

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
    public function getAadharUrl(Request $request)
    {
         /* Create response data */
         $response=[
            'data' => [],
            'msg' => '',
            'statusCode'=> 200
        ];
        $data=$request->all();
        try{

            $client = new Client();
            $headers = [
              'Content-Type' => 'application/json'];
            $body = '{
              "company": "viindhya",
              "workflowName": "kuberascheme",
              "secret_key": "k9txD5nWtwS6e38iiwDqL26Vb0vVi2iq",
              "notification": true,
              "unique_keys": {
                "referenceno": "7657567856548"
              }
            }';
           // $req = new GuzzleHttp("POST", 'https://live.meon.co.in/get_sso_route', $headers, $body);
           // $res = $client->sendAsync($req)->wait();
            //$response['data']= $res->getStatusCode();
            //$res= $client->send($req);
            // $res->then(
            //     function (ResponseInterface $res) {
            //         $response['data']= $res->getStatusCode();
            //         return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
            //     },
            //     function (RequestException $e) {
            //         $response['data']= [
            //             $e->getMessage(),
            //             $e->getRequest()->getMethod()
            //         ];
            //         return $this->sendResponse($response['statusCode'],$response['msg'],$response['data'],'');
            //     }
            // );

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
                $this->logMe(message:'end addContent() Exception',data:['file' => __FILE__, 'line' => __LINE__]);
                throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
            }
    }
}
