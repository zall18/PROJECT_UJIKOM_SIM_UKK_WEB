<?php

use App\Http\Controllers\AssessorController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $data['active'] = 'dashboard';
    return view('admin.dashboard', $data);
});
Route::get('/user/managment', [UserController::class, 'index']);
Route::get('/user/create', [UserController::class, 'createPage']);
Route::post('/user/create', [UserController::class, 'create']);
Route::get('/user/update/{id}', [UserController::class,'updatePage']);
Route::post('/user/update/{id}', [UserController::class,'update']);
Route::get('/user/delete/{id}', [UserController::class,'delete']);
Route::get('/assessor/managment', [AssessorController::class, 'index']);
Route::get('/major/managment', [MajorController::class, 'index']);
Route::get('/major/create', [MajorController::class, 'createPage']);
Route::get('/major/update/{id}', [MajorController::class, 'updatePage']);
Route::get('/major/delete/{id}', [MajorController::class, 'delete']);
Route::post('/major/create', [MajorController::class, 'create']);
Route::post('/major/update', [MajorController::class, 'update']);
Route::get('/student/managment', [StudentController::class,'index']);
Route::get('/admin/managment', [UserController::class,'Admins']);
Route::get('/major/student', [MajorController::class,'ms']);
Route::get('/major/student/{id}', [MajorController::class,'mstudent']);
