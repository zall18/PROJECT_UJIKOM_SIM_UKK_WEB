<?php

namespace App\Http\Controllers;

use App\Models\CompetencyStandard;
use App\Models\Major;
use App\Models\Student;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MajorController extends Controller
{
    public function index()
    {
        $data['majors'] = Major::all();
        $data['active'] = 'major';
        $title = 'Delete Major!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('admin.majorManage', $data);
    }

    public function createPage()
    {
        $data['active'] = 'major';
        return view('admin.majorCreate', $data);
    }

    //Create major
    public function create(Request $request)
    {
        $validation = $request->validate([
            'major_name' => ['required'],
            'description' => ['required']
        ]);

        if ($validation) {
            Major::create([
                'major_name' => $request->major_name,
                'description' => $request->description
            ]);

            alert::success('Major data', 'Success to create new major!');

            return redirect('/major/managment');
        } else {
            return back();
        }
    }

    //Update major
    public function updatePage(Request $request)
    {
        $data['major'] = Major::find($request->id);
        $data['active'] = 'major';

        return view('admin.majorUpdate', $data);
    }

    public function update(Request $request)
    {
        $validation = $request->validate([
            'major_name' => ['required'],
            'description' => ['required']
        ]);

        if ($validation) {
            Major::where('id', $request->id)->update([
                'major_name' => $request->major_name,
                'description' => $request->description
            ]);

            alert::success('Major data', 'Success to update major!');

            return redirect('/major/managment');
        } else {
            return back();
        }
    }

    //Delete major
    public function delete(Request $request)
    {

        $stundent = Student::where('major_id', $request->id)->get()->count();
        $standard = CompetencyStandard::where('major_id', $request->id)->get()->count();

        if ($stundent > 0 || $standard > 0) {
            Alert::error('Failed to delete', 'There is still student and competency standard that connect with this major');
            return back();
        } else {
            Major::where('id', $request->id)->delete();
            return redirect('major/managment');
        }

    }

    public function ms()
    {
        $data['majors'] = Major::all();
        $data['active'] = 'ms';
        return view('admin.majorStudent', $data);
    }

    //Show all student from the major
    public function mstudent(Request $request)
    {
        $data['students'] = Student::where('major_id', $request->id)->with('user')->get();
        $data['active'] = 'ms';
        return view('admin.majorStudentTable', $data);
    }
}
