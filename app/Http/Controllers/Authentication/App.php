<?php

namespace App\Http\Controllers\Authentication;

use App\Classes\Login\EmailLogin;
use App\Classes\Login\OtpLogin;
use App\Http\Controllers\Controller;
use App\Interfaces\Login\LoginMethod;
use Illuminate\Http\Request;

class App extends Controller implements LoginMethod
{

    protected $login;
    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->loginMethod(\request()->input('type'));
    }
    /**
     * @throws \Exception
     */
    public function loginMethod($type):void
    {
        if ($type=="email"):
            $this->login=new EmailLogin();
        elseif ($type=="otp"):
            $this->login=new OtpLogin();
        else:
            throw new \Exception("Invalid login type");
        endif;
    }

    public function register(Request $request)
    {
        return $this->login->register($request);
    }

    public function verified(Request $request)
    {
        return $this->login->verified($request);
    }
}
