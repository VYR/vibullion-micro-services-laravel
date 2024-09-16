<?php

namespace App\Repositories;

use App\Exceptions\GlobalException;
use App\Exceptions\GlobalExceptionHandler;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Arr;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
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

        $conditions=[
            ["user_details->signup_data->email",'=', $data['user_details']['signup_data']['email']],
            ["user_details->signup_data->website",'=', $data['user_details']['signup_data']['website']]
        ];
        $response=User::where($conditions)->first();
        if(!is_null($response)){
            return false;
        }else{
            try{
                return User::insert($data);
            }catch(\Exception $e){
                //return false;
                throw new GlobalException(errCode:200,data:$data, errMsg: 'User Already Existed');
            }

        }
    }
}
