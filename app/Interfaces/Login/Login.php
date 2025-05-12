<?php

namespace App\Interfaces\Login;

interface Login
{
    public function register($request);
    public function verified($request);

}
