<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserImport;

class StudentController extends Controller
{
    public function index(){
        $data['students'] = Student::all();
        $data['active'] = 'student';
        return view('admin.studentManage', $data);
    }

    public function studentImport()
    {
        $data['active'] = 'student';
        return view('admin.studentImport', $data);
    }

    public function import(Request $request)
    {
        // // Validasi file
        // $request->validate([
        //     'file' => 'required|mimes:xlsx,xls,csv',
        // ]);

        // // Proses import menggunakan Laravel Excel
        // Excel::import(new UserImport, $request->file('file'));

        // return redirect()->back()->with('success', 'Data berhasil diimpor!');
    }
}
