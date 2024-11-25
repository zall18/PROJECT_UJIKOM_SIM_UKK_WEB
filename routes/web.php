<?php

use App\Http\Controllers\AssessorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CompetencyElementController;
use App\Http\Controllers\CompetencyStandardController;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\admin;
use App\Models\Assessor;
use App\Models\CompetencyElement;
use App\Models\CompetencyStandard;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('guest')->group(function () {

    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/', function () {
        $data['active'] = 'dashboard';
        return view('login', $data);
    });
});

Route::get('/auth/logout', [AuthController::class, 'logout']);


Route::middleware('admin.session')->group(function () {

    //Admin Dashboard Route
    Route::get('/admin/dashboard', function () {
        $data['active'] = 'dashboard';
        $data['user_count'] = User::all()->count();
        $data['student_count'] = Student::all()->count();
        $data['assessor_count'] = Assessor::all()->count();
        $data['admin_count'] = User::where('role', 'admin')->get()->count();
        return view('admin.dashboard', $data);
    });

    //User Managment Route
    Route::get('/user/managment', [UserController::class, 'index']);
    Route::get('/user/create', [UserController::class, 'createPage']);
    Route::post('/user/create', [UserController::class, 'create']);
    Route::get('/user/update/{id}', [UserController::class, 'updatePage']);
    Route::post('/user/update/{id}', [UserController::class, 'update']);
    Route::delete('/user/delete/{id}', [UserController::class, 'delete']);

    //Assessor Managment Route
    Route::get('/assessor/managment', [AssessorController::class, 'index']);

    //Major Managment Route
    Route::get('/major/managment', [MajorController::class, 'index']);
    Route::get('/major/create', [MajorController::class, 'createPage']);
    Route::get('/major/update/{id}', [MajorController::class, 'updatePage']);
    Route::delete('/major/delete/{id}', [MajorController::class, 'delete']);
    Route::post('/major/create', [MajorController::class, 'create']);
    Route::post('/major/update', [MajorController::class, 'update']);

    //Student Managment Route
    Route::get('/student/managment', [StudentController::class, 'index']);
    Route::get('/student/create/import', [StudentController::class, 'studentImport']);
    Route::post('/student/create/excel', [ExcelController::class, 'import']);

    //Admin Managment Route
    Route::get('/admin/managment', [UserController::class, 'Admins']);

    //Major Student Managment Route
    Route::get('/major/student', [MajorController::class, 'ms']);
    Route::get('/major/student/{id}', [MajorController::class, 'mstudent']);

    //Me
    Route::get('/me', [UserController::class, 'userAdmin']);

    //Exam Result Report Admin Route
    Route::get('/exam-result', [CompetencyStandardController::class, 'cs_admin']);
    Route::get('/exam-result/competency-standard/{id}', [ExaminationController::class, 'reportAdmin']);

    //Competncy Standard & Element Route
    Route::get('/admin/competency-standard/managment', [CompetencyStandardController::class, 'indexAdmin']);
    Route::get('/admin/competency-standard/create', [CompetencyStandardController::class, 'adminCreatePage']);
    Route::post('/admin/competency-standard/create', [CompetencyStandardController::class, 'Admincreate']);
    Route::get('/admin/competency-standard/competency-elements/{id}', [CompetencyElementController::class, 'indexAdmin']);
    Route::post('/admin/competency-standard/competency-elements', [CompetencyElementController::class, 'admincreate']);
    Route::get('/admin/competency-standard/detail/{id}', [CompetencyStandardController::class, 'admindetailPage']);
    Route::get('/admin/competency-standard/update/{id}', [CompetencyStandardController::class, 'adminupdatePage']);
    Route::post('/admin/competency-standard/update/{id}', [CompetencyStandardController::class, 'adminupdate']);
    Route::delete('/competency-standard/delete/{id}', [CompetencyStandardController::class, 'admindelete']);
    Route::get('/admin/exam-results/report/{standardId}', [ExaminationController::class, 'fetchReport']);
    Route::get('/admin/exam/report/{id}/excel', [ExcelController::class, 'exportReport']);

    Route::get('/admin/competency-standard/competency-elements/update/{cid}/{id}', [CompetencyElementController::class, 'adminupdatePage']);
    Route::post('/admin/competency-standard/competency-elements/update/{cid}/{id}', [CompetencyElementController::class, 'adminupdate']);
    Route::delete('/admin/competency-standard/competency-elements/delete/{cid}/{id}', [CompetencyElementController::class, 'admindelete']);
});

Route::middleware('assessor.session')->group(function () {
    //Assessor Dashboard Route
    Route::get('/assessor/dashboard', function () {
        $data['active'] = 'dashboard';
        $data['competency_count'] = CompetencyStandard::where('assessor_id', Auth::user()->assessor->id)->get()->count();
        $data['student_count'] = Student::all()->count();
        return view('assessor.dashboard', $data);
    });
    //Competency Standard Route
    Route::get('/competency-standard/managment', [CompetencyStandardController::class, 'index']);
    Route::get('/competency-standard/create', [CompetencyStandardController::class, 'createPage']);
    Route::post('/competency-standard/create', [CompetencyStandardController::class, 'create']);
    Route::get('/competency-standard/detail/{id}', [CompetencyStandardController::class, 'detailPage']);
    Route::get('/competency-standard/update/{id}', [CompetencyStandardController::class, 'updatePage']);
    Route::post('/competency-standard/update/{id}', [CompetencyStandardController::class, 'update']);
    Route::delete('/competency-standard/delete/{id}', [CompetencyStandardController::class, 'delete']);

    //Competency Element Route
    Route::get('/competency-standard/competency-elements/{id}', [CompetencyElementController::class, 'index']);
    Route::post('/competency-standard/competency-elements', [CompetencyElementController::class, 'create']);
    Route::get('/competency-standard/competency-elements/update/{cid}/{id}', [CompetencyElementController::class, 'updatePage']);
    Route::post('/competency-standard/competency-elements/update/{cid}/{id}', [CompetencyElementController::class, 'update']);
    Route::delete('/competency-standard/competency-elements/delete/{cid}/{id}', [CompetencyElementController::class, 'delete']);

    //Exam Result and Report Route
    Route::get('/exam/result', [CompetencyElementController::class, 'examResult']);
    Route::get('/exam/result/report', [CompetencyElementController::class, 'examResultReport']);
    Route::get('/exam/result/{id}/student', [ExaminationController::class, 'result']);
    Route::get('/exam-result/{standard}', [ExaminationController::class, 'fetchExamResult']);
    Route::get('/exam-results/report/{standardId}', [ExaminationController::class, 'fetchReport']);
    Route::get('/exam/report/competency-standard/{id}', [ExaminationController::class, 'report']);
    Route::get('/exam/report/{id}/excel', [ExcelController::class, 'exportReport']);

    //Assessor Profile Route
    Route::get('/assessor/me', [UserController::class, 'userAssessor']);
    Route::post('/assessor/me', [UserController::class, 'assessorProfileUpdate']);
    Route::get('/assessor/update', [UserController::class, 'assessorUpdate']);

    //Assessment Route
    Route::get('/assesment', [ExaminationController::class, 'assessmenShow']);
    Route::get('/assesment/competency-standard/{id}', [ExaminationController::class, 'assessmentStudent']);
    Route::get('/assesment/competency-standard/{standard_id}/student/{id}', [ExaminationController::class, 'assessmentStudentExam']);
    Route::get('/assesment/fetch/competency-standard/{standard_id}/student/{id}', [ExaminationController::class, 'fetchassessmentStudentExam']);
    Route::post('/assessment/grading/{id}', [ExaminationController::class, 'grading']);
});

Route::middleware('student.session')->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard']);

    Route::get('/student/exam-result', [ExaminationController::class, 'resultStudent']);
    Route::get('/student/profile', [StudentController::class, 'studentProfile']);
    Route::get('/student/profile/update', [StudentController::class, 'profileUpdate']);
    Route::post('/student/profile/update', [StudentController::class, 'update']);
    Route::post('/exam-result/certificate', [CertificateController::class, 'generateCertificate']);

});

