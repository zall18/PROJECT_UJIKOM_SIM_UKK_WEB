<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index()
    {
        $data['majors'] = Major::all();
        return view('admin.majorManage', $data);
    }

    public function createPage()
    {
        return view('admin.majorCreate');
    }

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

            return redirect('/major/managment');
        } else {
            return back();
        }
    }

    public function updatePage(Request $request)
    {
        $data['major'] = Major::find($request->id);
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
            return redirect('/major/managment');
        } else {
            return back();
        }
    }

    public function delete(Request $request)
    {
        Major::where('id', $request->id)->delete();
        return redirect('major/managment');
    }
}