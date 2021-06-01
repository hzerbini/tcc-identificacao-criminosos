<?php

namespace App\Http\Controllers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'rememberMe' => 'nullable|boolean'
        ]);

       if(auth()->attempt($request->only('email', 'password'), $request->rememberMe)){
           return response()->noContent();
       }
       throw new AuthenticationException;
    }
}
