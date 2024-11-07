<?php

namespace App\Http\Controllers;

use App\Models\CompetencyElement;
use Illuminate\Http\Request;

class CompetencyElementController extends Controller
{
    public function index(Request $request)
    {
        $data['active'] = 'competency';
        $data['id'] = $request->id;
        return view("assessor.elementCreate", $data);
    }

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

        return redirect('/competency-standard/managment');

    }

    public function updatePage(Request $request)
    {
        $data['criteria'] = CompetencyElement::where('id', $request->id)->first();
        $data['active'] = 'competency';
        $data['cid'] = $request->cid;
        return view('assessor.elemenetUpdate', $data);
    }

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

    public function delete(Request $request)
    {
        CompetencyElement::where('id', $request->id)->delete();

        return redirect('/competency-standard/detail/' . $request->cid);

    }
}
