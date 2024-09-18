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
        $this->logMe(message:'start signup()',data:['file' => __FILE__, 'line' => __LINE__]);
        $conditions=[
            ["user_details->signup_data->email",'=', $data['user_details']['signup_data']['email']],
            ["user_details->signup_data->website",'=', $data['user_details']['signup_data']['website']]
        ];
        $response=User::where($conditions)->first();
       // return $response;
        if(!is_null($response)){
            return false;
        }else{
            try{
                $user=new User();
                $user->fill($data);
                $user->user_details=$data['user_details'];
                return $user->save();
            }catch(\Exception $e){
                //return false;
                throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
            }
        }
    }
    public function login(array $data){
        $this->logMe(message:'start login()',data:['file' => __FILE__, 'line' => __LINE__]);
        try{
            $conditions=[
                ["user_details->signup_data->email",'=', $data['email']],
                ["user_details->signup_data->website",'=', $data['website']]
            ];
             $response=User::where($conditions)->first();
             //return  $data;
             //return  $response;
            if(is_null($response)){
                return [];
            }else{
                $user = User::where('email', $response['userId'])->first();

                if (! $user || ! Hash::check($data['password'], $user->password)) {
                    // throw ValidationException::withMessages([
                    //     'email' => ['The provided credentials are incorrect.'],
                    // ]);
                    throw new GlobalException(errCode:404,data:$user,
                    errMsg: 'The provided credentials are incorrect.');
                }
                return [
                    'user'=> $user,
                    'token' => $user->createToken($response['userId'])->plainTextToken
                ];
            }

        }catch(\Exception $e){
            //return false;
            throw new GlobalException(errCode:404,data:$data, errMsg: $e->getMessage());
        }

    }
}
