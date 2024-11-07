<?php

namespace App\Http\Controllers;

use App\Models\Competency_Standard;
use App\Models\CompetencyElement;
use App\Models\CompetencyStandard;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function create(Request $request)
    {
        $validate = $request->validate([
            'unit_code' => ['required'],
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

    public function detailPage(Request $request)
    {
        $data['competency'] = CompetencyStandard::where('id', $request->id)->first();
        $data['elements'] = CompetencyElement::where('competency_standard_id', $request->id)->get();
        $data['active'] = 'competency';
        return view('assessor.competencyDetail', $data);
    }
}
