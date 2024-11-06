<?php

namespace App\Http\Controllers;

use App\Models\Assessor;
use Illuminate\Http\Request;

class AssessorController extends Controller
{
    public function index()
    {
        $data['assessors'] = Assessor::all();
        $data['active'] = 'assessor';
        return view('admin.assessorManage', $data);
    }
}
