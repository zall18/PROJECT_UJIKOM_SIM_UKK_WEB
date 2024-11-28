<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    //Login
    public function login(Request $request)
    {

        $validate = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validate) {
            $remember = $request->has('remember');


            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {

                if(Auth::user()->is_active == '0'){
                    Auth::logout();
                    Alert::toast('Login Failed', 'error');
                    return redirect('/');
                }

                $role = Auth::user()->role;

                if ($role == 'admin')
                {
                    return redirect('/admin/dashboard');
                }else if($role == 'assessor'){
                    return redirect('/assessor/dashboard');
                }else if($role == 'student'){
                    return redirect('/student/dashboard');
                }

            }else{
                Alert::toast('Login Failed', 'error');
                return redirect('/');
            }

        }

    }

    //Logout
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
