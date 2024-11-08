<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

                $role = Auth::user()->role;

                if ($role == 'admin')
                {
                    return redirect('/admin/dashboard');
                }else if($role == 'assessor'){
                    return redirect('/assessor/dashboard');
                }

            }

        }

    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
