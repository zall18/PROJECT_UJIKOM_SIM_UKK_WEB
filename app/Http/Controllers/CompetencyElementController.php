<?php

namespace App\Http\Controllers;

use App\Models\CompetencyElement;
use Illuminate\Http\Request;

class CompetencyElementController extends Controller
{
    public function index(Request $request){
        $data['active'] = 'competency';
        $data['id'] = $request->id;
         return view("assessor.elementCreate", $data);
    }

    public function create(Request $request){
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
}
