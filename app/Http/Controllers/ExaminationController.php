<?php

namespace App\Http\Controllers;

use App\Models\CompetencyStandard;
use App\Models\Examination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExaminationController extends Controller
{
    public function result()
    {
        $data['competencies'] = CompetencyStandard::where('assessor_id', Auth::user()->assessor->id)->withCount('competency_elements')->get();
        $data['examintions'] = Examination::all();
        return view('assessor.examResultStudent', $data);

    }
}
