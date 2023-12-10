<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthRequests extends Controller {


    public function __construct(Request $request)
   {
     return  $this->validate(
         $request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5'
         ]
      );

   }

}
