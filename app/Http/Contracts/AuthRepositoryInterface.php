<?php

namespace App\Http\Contracts;

use Illuminate\Http\Request;

interface AuthRepositoryInterface
{

    public function getUserLogin(Request $request);
    public function userRegister(Request $request);
    public function getUserInfo();

}
