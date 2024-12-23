<?php

namespace App\Http\Controllers;

use App\Models\Assessor;
use App\Models\Major;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use RealRashid\SweetAlert\Toaster;

class UserController extends Controller
{
    public function index()
    {
        $data['users'] = User::where('id', '!=', Auth::user()->id)->get();
        $data['active'] = 'user';
        $title = 'Delete Major!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('admin.userManage', $data);
    }

    public function createPage()
    {
        $data['majors'] = Major::all();
        $data['active'] = 'user';
        return view('admin.userCreate', $data);
    }

    //Create user include the role
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

                Alert::success('User Data', 'Success to create student');

                return redirect('/user/managment');
            } else if ($request->role == 'assessor') {
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
                Alert::success('User Data', 'Success to create assessor');


                return redirect('/user/managment');
            } else {
                User::create([
                    'full_name' => $request->full_name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'phone' => $request->phone,
                    'role' => "admin",
                    'is_active' => 1
                ]);
                Alert::success('User Data', 'Success to create admin');

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

    //Update user include the role
    public function update(Request $request)
    {
        $validate = $request->validate([
            'full_name' => ['required'],
            'username' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'role' => ['required'],
            'is_active' => ['required'],
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
                        'is_active' => $request->is_active
                    ]);

                    Student::where('id', $request->id)->update([
                        'nisn' => $request->nisn,
                        'grade_level' => $request->grade_level,
                        'major_id' => $request->major_id,
                    ]);
                }


                return redirect('/user/managment');
            } else if ($request->role == 'assessor') {
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
                        'is_active' => $request->is_active
                    ]);

                    Assessor::where('id', $request->id)->update([
                        'assessor_type' => $request->assessor_type,
                        'description' => $request->description
                    ]);
                }

                return redirect('/user/managment');
            } else {
                User::where('id', $request->id)->update([
                    'full_name' => $request->full_name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => $request->password != null ? bcrypt($request->password) : DB::raw('password'),
                    'phone' => $request->phone,
                    'role' => "admin",
                    'is_active' => $request->is_active
                ]);
                return back();
            }
        }
    }

    //Delete user
    public function delete(Request $request)
    {
        User::where('id', $request->id)->delete();
        return redirect('/user/managment');
    }

    //Show user role admin
    public function Admins()
    {
        $data['admins'] = User::where('role', 'admin')->where('id', '!=', Auth::user()->id)->get();
        $data['active'] = 'admin';
        return view('admin.adminManage', $data);
    }

    //Show profile admin
    public function userAdmin()
    {
        $data['user'] = Auth::user();
        $data['active'] = 'dashboard';
        return view('admin.adminProfile', $data);
    }

    //Show profile assessor
    public function userAssessor()
    {
        $data['user'] = Auth::user();
        $data['active'] = 'dashboard';
        return view('assessor.assessorProfile', $data);
    }

    public function assessorUpdate()
    {
        $data['user'] = Auth::user();
        $data['active'] = 'dashboard';
        return view('assessor.profileUpdate', $data);
    }

    //Update profile assessor
    public function assessorProfileUpdate(Request $request)
    {
        $validate = $request->validate([
            'username' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'role' => ['required']
        ]);

        if ($validate) {
            User::where('id', Auth::user()->id)->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password != null ? bcrypt($request->password) : DB::raw('password'),
                'phone' => $request->phone,
            ]);

            return redirect('/assessor/me');
        }
    }
}
