<?php

namespace App\Http\Controllers;

use App\Models\CompetencyElement;
use App\Models\CompetencyStandard;
use App\Models\Examination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class CompetencyElementController extends Controller
{
    public function index(Request $request)
    {
        $data['active'] = 'competency';
        $data['id'] = $request->id;
        return view("assessor.elementCreate", $data);
    }
    public function indexAdmin(Request $request)
    {
        $data['active'] = 'competency';
        $data['id'] = $request->id;
        return view("admin.elementCreate", $data);
    }

    // Create Competency Elements
    public function create(Request $request)
    {
        // dd($request->all());

        $criterias = $request->criterias;

        foreach ($criterias as $key => $criteria) {
            if ($criteria != null) {
                CompetencyElement::create([
                    'criteria' => $criteria,
                    'competency_standard_id' => $request->id
                ]);


            }
        }

        Alert::success('Competency', 'Success to create Competency Standard and Competency Elements');


        return redirect('/competency-standard/managment');

    }
    public function admincreate(Request $request)
    {
        // dd($request->all());

        $criterias = $request->criterias;

        foreach ($criterias as $key => $criteria) {
            if ($criteria != null) {
                CompetencyElement::create([
                    'criteria' => $criteria,
                    'competency_standard_id' => $request->id
                ]);


            }
        }

        Alert::success('Competency', 'Success to create Competency Standard and Competency Elements');


        return redirect('/admin/competency-standard/managment');

    }


    public function updatePage(Request $request)
    {
        $data['criteria'] = CompetencyElement::where('id', $request->id)->first();
        $data['active'] = 'competency';
        $data['cid'] = $request->cid;
        return view('assessor.elemenetUpdate', $data);
    }
    public function adminupdatePage(Request $request)
    {
        $data['criteria'] = CompetencyElement::where('id', $request->id)->first();
        $data['active'] = 'competency';
        $data['cid'] = $request->cid;
        return view('admin.elemenetUpdate', $data);
    }

    //Update competency standard
    public function update(Request $request)
    {
        $validate = $request->validate([
            'criteria' => ['required']
        ]);

        if ($validate) {

            CompetencyElement::where('id', $request->id)->update([
                'criteria' => $request->criteria
            ]);

            return redirect('/competency-standard/detail/' . $request->cid);

        }
    }
    public function adminupdate(Request $request)
    {
        $validate = $request->validate([
            'criteria' => ['required']
        ]);

        if ($validate) {

            CompetencyElement::where('id', $request->id)->update([
                'criteria' => $request->criteria
            ]);

            return redirect('/admin/competency-standard/detail/' . $request->cid);

        }
    }

    //Delete competency standard
    public function delete(Request $request)
    {

        $exam = Examination::where('element_id', $request->id)->get()->count();

        if ($exam > 0){
            Alert::error('There are still related exam results', 'Failed to delete');
            return back();
        }else{
            CompetencyElement::where('id', $request->id)->delete();

            Alert::success('Competency', 'Success to delete data!');

            return redirect('/competency-standard/detail/' . $request->cid);
        }



    }
    public function admindelete(Request $request)
    {
        $exam = Examination::where('element_id', $request->id)->get()->count();

        if ($exam > 0){
            Alert::error('There are still related exam results', 'Failed to delete');
            return back();
        }else{
            CompetencyElement::where('id', $request->id)->delete();

            Alert::success('Competency', 'Success to delete data!');

            return redirect('/admin/competency-standard/detail/' . $request->cid);

        }
    }

    //Show all competency standard from assessor that made the competency standard
    public function examResult()
    {
        $data['active'] = 'examResult';
        $data['competencies'] = CompetencyStandard::where('assessor_id', Auth::user()->assessor->id)->get();
        return view('assessor.examResult', $data);
    }

    //Show all competency standard from assessor that made the competency standard
    public function examResultReport()
    {
        $data['active'] = 'examResultReport';
        $data['competencies'] = CompetencyStandard::where('assessor_id', Auth::user()->assessor->id)->get();
        return view('assessor.examResultReport', $data);
    }
}
