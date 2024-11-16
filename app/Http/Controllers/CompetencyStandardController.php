<?php

namespace App\Http\Controllers;

use App\Models\Competency_Standard;
use App\Models\CompetencyElement;
use App\Models\CompetencyStandard;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class CompetencyStandardController extends Controller
{
    public function index()
    {
        $data['competencies'] = CompetencyStandard::all();
        $data['active'] = 'competency';
        return view('assessor.competencyManage', $data);
    }

    public function createPage()
    {
        $data['active'] = 'competency';
        $data['majors'] = Major::all();
        return view('assessor.competencyCreate', $data);
    }

    //Create competency standard and redirect to create competency elements
    public function create(Request $request)
    {
        $validate = $request->validate([
            'unit_code' => ['required', 'unique:competency_standards,unit_code'],
            'unit_title' => ['required'],
            'unit_description' => ['required'],
            'major_id' => ['required'],
        ]);

        if ($validate) {

            $id = CompetencyStandard::create([
                'unit_code' => strtoupper($request->unit_code),
                'unit_title' => $request->unit_title,
                'unit_description' => $request->unit_description,
                'major_id' => $request->major_id,
                'assessor_id' => Auth::user()->assessor->id
            ])->id;

            return redirect('/competency-standard/competency-elements/' . $id);
        }
    }

    //Show detail Competency standard and the elements
    public function detailPage(Request $request)
    {
        $data['competency'] = CompetencyStandard::where('id', $request->id)->first();
        $data['elements'] = CompetencyElement::where('competency_standard_id', $request->id)->get();
        $data['active'] = 'competency';
        $title = 'Delete Part of Competency';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('assessor.competencyDetail', $data);
    }

    //Delete competency standard and the elements
    public function delete(Request $request)
    {

        CompetencyElement::where('competency_standard_id', $request->id)->delete();
        CompetencyStandard::where('id', $request->id)->delete();
        Alert::success('Competency', 'Success to delete data!');
        return redirect('/competency-standard/managment');

    }

    public function updatePage(Request $request)
    {
        $data['competency'] = CompetencyStandard::where('id', $request->id)->first();
        $data['active'] = 'competency';
        $data['majors'] = Major::all();
        $data['id'] = $request->id;
        return view('assessor.competencyUpdate', $data);
    }

    //Update competency standard
    public function update(Request $request)
    {
        $validate = $request->validate([
            // 'unit_code' => ['required', 'unique:competency_standards,unit_code'],
            'unit_title' => ['required'],
            'unit_description' => ['required'],
            'major_id' => ['required'],
        ]);

        if ($validate) {

            CompetencyStandard::where('id', $request->id)->update([
                'unit_code' => strtoupper($request->unit_code),
                'unit_title' => $request->unit_title,
                'unit_description' => $request->unit_description,
                'major_id' => $request->major_id,
                'assessor_id' => Auth::user()->assessor->id
            ]);

            return redirect('/competency-standard/detail/' . $request->id);
        }
    }

    public function cs_admin()
    {
        $data['active'] = 'examResultReport';
        $data['competencies'] = CompetencyStandard::all();
        return view('admin.examReport', $data);
    }
}
