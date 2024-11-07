<?php

namespace App\Http\Controllers;

use App\Models\Assessor;
use App\Models\Major;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $data['users'] = User::all();
        $data['active'] = 'user';
        return view('admin.userManage', $data);
    }

    public function createPage()
    {
        $data['majors'] = Major::all();
        $data['active'] = 'user';
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
            }else if ($request->role == 'assessor') {
                $assessorValidate = $request->validate([
                    'assessor_type' => ['required'],
                    'description' => ['required']
                ]);

                if ($assessorValidate) {
                    $userId = User::create([
                        'full_name' => $request->full_name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'password' => bcrypt($request->password),
                        'phone' => $request->phone,
                        'role' => $request->role,
                        'is_active' => 1
                    ])->id;

                    Assessor::create([
                        'user_id' => $userId,
                        'assessor_type' => $request->assessor_type,
                        'description' => $request->description
                    ]);
                }

                return redirect('/user/managment');
            }else{
                User::create([
                    'full_name' => $request->full_name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'phone' => $request->phone,
                    'role' => "admin",
                    'is_active' => 1
                ]);
                return redirect('/user/managment');
            }
        }

    }

    public function updatePage(Request $request)
    {

        $data['user'] = User::where('id', $request->id)->first();
        $data['majors'] = Major::all();
        $data['active'] = 'user';
        return view('admin.userUpdate', $data);

    }

    public function update(Request $request){
        $validate = $request->validate([
            'full_name' => ['required'],
            'username' => ['required'],
            'email' => ['required', 'email'],
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
                    User::where('id', $request->id)->update([
                        'full_name' => $request->full_name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'password' => $request->password != null ? bcrypt($request->password) : DB::raw('password'),
                        'phone' => $request->phone,
                        'role' => $request->role,
                        'is_active' => 1
                    ]);

                    Student::where('id', $request->id)->update([
                        'nisn' => $request->nisn,
                        'grade_level' => $request->grade_level,
                        'major_id' => $request->major_id,
                    ]);
                }

                return redirect('/user/managment');
            }else if ($request->role == 'assessor') {
                $assessorValidate = $request->validate([
                    'assessor_type' => ['required'],
                    'description' => ['required']
                ]);

                if ($assessorValidate) {
                    User::where('id', $request->id)->update([
                        'full_name' => $request->full_name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'password' => $request->password != null ? bcrypt($request->password) : DB::raw('password'),
                        'phone' => $request->phone,
                        'role' => $request->role,
                        'is_active' => 1
                    ]);

                    Assessor::where('id', $request->id)->update([
                        'assessor_type' => $request->assessor_type,
                        'description' => $request->description
                    ]);
                }

                return redirect('/user/managment');
            }else{
                User::where('id', $request->id)->update([
                    'full_name' => $request->full_name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => $request->password != null ? bcrypt($request->password) : DB::raw('password'),
                    'phone' => $request->phone,
                    'role' => "admin",
                    'is_active' => 1
                ]);
                return redirect('/user/managment');
            }
        }
    }

    public function delete(Request $request){
        User::where('id', $request->id)->delete();
        return redirect('/user/managment');
    }

    public function Admins(){
        $data['admins'] = User::where('role', 'admin')->get();
        $data['active'] = 'admin';
        return view('admin.adminManage', $data);
    }

    public function userAdmin()
    {
        $data['user'] = Auth::user();
        $data['active'] = 'dashboard';
        return view('admin.adminProfile', $data);
    }
}
