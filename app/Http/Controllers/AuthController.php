<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
