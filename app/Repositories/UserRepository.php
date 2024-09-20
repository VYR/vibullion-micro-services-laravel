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
            //return false;
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
            //return  $data;
            // return  $response;
            if(is_null($response)){
                return $response;
            }
            else{
                // $user = User::where('email', $response['userId'])->first();

                // if (! $user || ! Hash::check($data['password'], $user->password)) {
                //     // throw ValidationException::withMessages([
                //     //     'email' => ['The provided credentials are incorrect.'],
                //     // ]);
                //     throw new GlobalException(errCode:404,data:$user,
                //     errMsg: 'The provided credentials are incorrect.');
                // }
                return [
                    'user'=> $response,
                    'token' => $response->createToken($response->email)->plainTextToken
                ];
            }

        }catch(\Exception $e){
            //return false;
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }

    }
}
