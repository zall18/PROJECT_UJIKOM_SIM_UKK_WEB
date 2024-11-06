<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(){
        $data['students'] = Student::all();
        $data['active'] = 'student';
        return view('admin.studentManage', $data);
    }
}
