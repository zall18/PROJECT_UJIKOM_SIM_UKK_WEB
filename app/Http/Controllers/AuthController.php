<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validate = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validate) {

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect('/dashboard');
            }

        }

    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
