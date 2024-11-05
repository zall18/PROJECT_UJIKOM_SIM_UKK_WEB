<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssessorController extends Controller
{
    public function index()
    {
        return view('admin.assessorManage');
    }
}
