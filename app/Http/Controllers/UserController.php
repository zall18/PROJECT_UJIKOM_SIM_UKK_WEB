<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data['users'] = User::all();
        return view('admin.userManage', $data);
    }

    public function createPage()
    {
        $data['majors'] = Major::all();
        return view('admin.userCreate', $data);
    }

    public function create(Request $request)
    {

        $validate = $request->validate([
            'full_name' => ['required'],
            'username' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:4'],
            'phone' => ['required'],
            'role' => ['required']
        ]);

        if ($validate) {

            if ($request->role == 'student') {
                $userValidate = $request->validate([
                    'nisn' => ['required'],
                    'grade_level' => ['required'],
                    'major_id' => ['required']
                ]);

                if ($userValidate) {
                    $userId = User::create([
                        'full_name' => $request->full_name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'password' => bcrypt($request->password),
                        'phone' => $request->phone,
                        'role' => $request->role,
                        'is_active' => 1
                    ])->id;

                    Student::create([
                        'nisn' => $request->nisn,
                        'grade_level' => $request->grade_level,
                        'major_id' => $request->major_id,
                        'user_id' => $userId
                    ]);
                }

                return redirect('/user/managment');
            }

        }

    }
}
