<?php

namespace App\Http\Controllers;

use App\Models\Assessor;
use Illuminate\Http\Request;

class AssessorController extends Controller
{
    //Show all Assessor
    public function index()
    {
        $data['assessors'] = Assessor::with('user')->get();
        $data['active'] = 'assessor';
        return view('admin.assessorManage', $data);
    }
}
