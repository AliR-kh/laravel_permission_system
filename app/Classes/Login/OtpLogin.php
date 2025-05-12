<?php

namespace App\Classes\Login;

use App\Interfaces\Login\Login;
use App\Models\Otp;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class OtpLogin implements Login
{
    use ApiResponse;

    public $user;

    public function register($request)
    {
        $validator = $this->registerValidate($request);
        if ($validator->fails()):
            return self::badRequest(error: $validator->errors());
        endif;
        $phone = $validator->validated()['phone'];
        $this->user = User::query()->firstOrCreate(['phone' => $phone]);
        return $this->checkOtp();
    }

    public function verified($request)
    {
        $validator = $this->verifiedValidate($request);
        if ($validator->fails()):
            return self::badRequest(error: $validator->errors());
        endif;
        $phone = $validator->validated()['phone'];
        $code = $validator->validated()['code'];
        $this->user = User::query()->where(['phone' => $phone])->first();
        $otp = $this->user->otps->where('status', 'pending')->first();
        $now = Carbon::now();
        $diff = is_null($this->user->banned_at) ? null : $now->diffInSeconds(Carbon::parse($this->user->banned_at));
        if (is_null($diff) or $diff < 0):
            if (!is_null($otp)):
                $diff = $now->diffInSeconds(Carbon::parse($otp->created_at)->addMinute(config('otp.login_expired') ?? 5));
                if ($diff > 0):
                    if ($otp->code == $code):
                        $otp->update(['status' => 'verified']);
                        $token = $this->user->createToken("web")->plainTextToken;
                        $this->user->update(["status" => "login", "banned_at" => null]);
                        return self::successResponse("Ok", data: ["token" => $token]);
                    else:
                        $otp->update(["attempts" => $otp->attempts + 1]);
                        if ($otp->attempts > (config("otp.login_attempts") ?? 5)):
                            $otp->update(["status" => "failed"]);
                            $this->user->update(["banned_at" => Carbon::now()]);
                        endif;
                        return self::badRequest('not Ok ', error: ['error' => 'OTP is wrong']);
                    endif;
                else:
                    $otp->update(["status" => "expired"]);
                    return self::forbidden('otp is expired');
                endif;
            else:
                return self::badRequest('not Ok ', error: ['error' => 'OTP is expired']);
            endif;
        else:
            return self::forbidden('you are banned', error: ["time" => -$diff]);
        endif;
    }


    public function checkOtp(): string
    {
        $now = Carbon::now();
        $otp = $this->user->otps->where('status', 'pending')->first();
        $diff = is_null($this->user->banned_at) ? null : $now->diffInSeconds(Carbon::parse($this->user->banned_at));
        if (!is_null($diff) and $diff < 0):
            $this->user->update(['banned_at' => null]);
            return $this->createOtp();
        elseif (!is_null($diff) and $diff < 0):
            return self::forbidden('you are banned', error: ["time" => -$diff]);
        endif;
        if (!is_null($otp)):
            $diff = is_null($otp) ? null : $now->diffInSeconds(Carbon::parse($otp->created_at)->addMinute(config('otp.login_new_otp') ?? 1));
            if (is_null($diff) or $diff < 0):
                $otp->update(["status" => "expired"]);
                return $this->createOtp();
            else:
                return self::tooManyRequests(message: "there is an active otp");
            endif;
        else:
            return $this->createOtp();
        endif;
    }

    public function createOtp()
    {
        $code = 1234;
        try {
            Otp::query()->create([
                "user_id" => $this->user->id,
                "code" => $code,
                "status" => 'pending',
            ]);
            return self::successResponse(message: "Ok", data: ["code" => $code]);
        } catch (\Exception $exception) {
            return self::serverError(message: "there is wrong", error: $exception->getMessage());
        }
    }

    function registerValidate($request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            "phone" => "required|string",
        ]);
    }

    function verifiedValidate($request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            "phone" => "required|exists:users,phone",
            "code" => "required|string",
        ]);
    }

}
