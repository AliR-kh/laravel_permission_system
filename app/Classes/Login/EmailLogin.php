<?php

namespace App\Classes\Login;

use App\Interfaces\Login\Login;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmailLogin implements Login
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */

    public function register($request)
    {
        $validator =$this->registerValidate($request);
        if ($validator->fails()):
            return self::badRequest(error: $validator->errors());
        endif;
        $email=$validator->validated()['email'];
        User::query()->firstOrCreate(['email'=>$email]);
        return self::successResponse("Ok");
    }

    public function verified($request)
    {
        $validator =$this->verifiedValidate($request);
        if ($validator->fails()):
            return self::badRequest(error: $validator->errors());
        endif;
        $email=$validator->validated()['email'];
        $password=$validator->validated()['password'];
        $user=User::query()->where('email',$email)->first();
        if ($user->status=="no-login"):
            $user->update(["password"=>Hash::make($password),"status"=>"login"]);
            $token=$user->createToken("web")->plainTextToken;
            return self::successResponse("Ok",data:["token"=>$token]);
        elseif ($user->status=="login" or $user->status=="deactive"):
            if (Hash::check($password,$user->password)):
                $token=$user->createToken("web")->plainTextToken;
                $user->update(["status"=>"login"]);
                return self::successResponse("Ok",data:["token"=>$token]);
            else:
                return self::badRequest(error:"Wrong password");
            endif;
        endif;

    }


    public function registerValidate($request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(),[
            "email" => "required|email",
        ]);
    }
    public function verifiedValidate($request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(),[
            "email" => "required|email|exists:users,email",
            "password"=>"required|string|min:6",
        ]);
    }
}
