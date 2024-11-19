<?php

namespace App\Http\Controllers;

use App\Models\CompetencyElement;
use App\Models\CompetencyStandard;
use App\Models\Examination;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExaminationController extends Controller
{

    //Show exam result from competency's assessor
    public function result(Request $request)
    {
        $standard = CompetencyStandard::where('assessor_id', Auth::user()->assessor->id)->withCount('competency_elements')->first();
        // Mendapatkan data ujian murid berdasarkan standard yang dipilih
        $examinations = Examination::where('standard_id', $request->id)->get();
        $active = 'examResult';

        // Mendapatkan daftar murid yang mengikuti ujian pada standar kompetensi ini
        $students = $examinations->groupBy('student_id')->map(function ($exams) use ($standard) {
            $totalElements = $standard->competency_elements_count;
            $completedElements = $exams->where('status', 1)->count(); // Menghitung elemen yang statusnya kompeten

            // Menghitung nilai akhir dalam bentuk persentase
            $finalScore = round(($completedElements / $totalElements) * 100);

            // Menentukan status kompeten atau tidak kompeten berdasarkan persentase (misalnya kompeten jika >= 75%)
            $status = $finalScore >= 75 ? 'Competent' : 'Not Compotent';

            // dd($status);

            return [
                'student_id' => $exams->first()->id,
                'student_name' => $exams->first()->student->user->full_name,
                'final_score' => $finalScore,
                'status' => $status
            ];
        });

        // Kirim data ke tampilan
        return view('assessor.examResultStudent', compact('standard', 'students', 'active'));

    }

    //Show exam result report from competency's assessor
    public function report(Request $request)
    {
        $data['elements'] = CompetencyElement::where('competency_standard_id', $request->id)->get();
        $data['standard'] = CompetencyStandard::where('id', $request->id)->first();
        $data['active'] = 'examResultReport';
        $standard = CompetencyStandard::where('assessor_id', Auth::user()->assessor->id)->withCount('competency_elements')->first();
        // Mendapatkan data ujian murid berdasarkan standard yang dipilih
        $examinations = Examination::where('standard_id', $request->id)->get();
        $data['students'] = $examinations->groupBy('student_id')->map(function ($exams) use ($standard) {
            $totalElements = $standard->competency_elements_count;
            $completedElements = $exams->where('status', 1)->count(); // Menghitung elemen yang statusnya kompeten
            $finalScore = round(($completedElements / $totalElements) * 100);
            $status = $finalScore >= 75 ? 'Competent' : 'Not Competent';
            $elementsStatus = $standard->competency_elements->sortBy('code')->map(function ($element) use ($exams) {
                $exam = $exams->firstWhere('element_id', $element->id);
                return [
                    'status' => $exam ? ($exam->status == 1 ? 'Kompeten' : 'Belum Kompeten') : 'Belum Dinilai',
                    'comments' => $exam ? $exam->comments : '-'
                ];
            });

            return [
                'student_id' => $exams->first()->id,
                'student_name' => $exams->first()->student->user->full_name,
                'elements' => $elementsStatus,
                'final_score' => $finalScore,
                'status' => $status
            ];
        });

        // dd($data['students']);
        return view('assessor.examResultReportStudent', $data);
    }

    //show all exam result
    public function reportAdmin(Request $request)
    {
        $data['elements'] = CompetencyElement::where('competency_standard_id', $request->id)->get();
        $data['standard'] = CompetencyStandard::where('id', $request->id)->first();
        $data['active'] = 'examResultReport';
        $standard = CompetencyStandard::withCount('competency_elements')->first();
        // Mendapatkan data ujian murid berdasarkan standard yang dipilih
        $examinations = Examination::where('standard_id', $request->id)->get();
        $data['students'] = $examinations->groupBy('student_id')->map(function ($exams) use ($standard) {
            $totalElements = $standard->competency_elements_count;
            $completedElements = $exams->where('status', 1)->count(); // Menghitung elemen yang statusnya kompeten
            $finalScore = round(($completedElements / $totalElements) * 100);
            $status = $finalScore >= 75 ? 'Competent' : 'Not Competent';
            $elementsStatus = $standard->competency_elements->sortBy('code')->map(function ($element) use ($exams) {
                $exam = $exams->firstWhere('element_id', $element->id);
                return [
                    'status' => $exam ? ($exam->status == 1 ? 'Kompeten' : 'Belum Kompeten') : 'Belum Dinilai',
                    'comments' => $exam ? $exam->comments : '-'
                ];
            });

            return [
                'student_id' => $exams->first()->id,
                'student_name' => $exams->first()->student->user->full_name,
                'elements' => $elementsStatus,
                'final_score' => $finalScore,
                'status' => $status
            ];
        });

        // dd($data['students']);
        return view('admin.examReportDetail', $data);
    }

    public function assessmenShow()
    {
        $user = Auth::user();
        $data['competencies'] = CompetencyStandard::where('assessor_id', $user->assessor->id)->get();
        $data['active'] = 'assessment';
        return view('assessor.assessment', $data);
    }

    public function assessmentStudent(Request $request)
    {
        $standard = CompetencyStandard::where('id', $request->id)->first();
        $data['standard'] = $standard;
        $data['students'] = Student::where('major_id', $standard->major_id)->get();
        $data['active'] = 'student';
        return view('assessor.assessmentStudent', $data);
    }

    public function assessmentStudentExam(Request $request)
    {
        $standard = CompetencyStandard::where('id', $request->standard_id)->first();
        $data['standard'] = $standard;
        $data['student'] = Student::where('id', $request->id)->first();
        $data['elements'] = CompetencyElement::where('competency_standard_id', $request->standard_id)->get();
        $data['examinations'] = Examination::where('standard_id', $request->standard_id)->where('student_id', $request->id)->get();
        $data['active'] = 'student';
        return view('assessor.assessmentStudent', $data);
    }
}
