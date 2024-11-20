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
        $standard = CompetencyStandard::where('id', $request->id)->withCount('competency_elements')->first();
        // Mendapatkan data ujian murid berdasarkan standard yang dipilih
        $examinations = Examination::where('standard_id', $request->id)->get();
        $active = 'examResult';

        // Mendapatkan daftar murid yang mengikuti ujian pada standar kompetensi ini
        $students = $examinations->groupBy('student_id')->map(function ($exams) use ($standard) {
            // Pastikan total elemen dihitung langsung dari data relasi
            $totalElements = $standard->competency_elements->count();

            // Hitung elemen kompeten secara unik berdasarkan element_id
            $completedElements = $exams->where('status', 1)->unique('element_id')->count();

            // Menghitung nilai akhir dalam bentuk persentase
            $finalScore = $totalElements > 0 ? round(($completedElements / $totalElements) * 100) : 0;

            // Menentukan status kompeten atau tidak kompeten
            $status = $finalScore >= 75 ? 'Competent' : 'Not Competent';
            // dd([
            //     'total_elements' => $totalElements,
            //     'completed_elements' => $completedElements,
            //     'exams' => $exams->toArray(),
            // ]);

            return [
                'student_id' => $exams->first()->student_id,
                'student_name' => $exams->first()->student->user->full_name,
                'final_score' => $finalScore,
                'status' => $status,
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
        $standard = CompetencyStandard::where('id', $request->id)->withCount('competency_elements')->first();
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
        return view('assessor.assesmentStudentExam', $data);
    }

    public function grading(Request $request) {
        // dd($request->status);

        $elements = $request->elements;

        foreach ($elements as $key => $value) {
            // dd($request->id);
            // dd([$request->status0, $request->status1]);
            $statusKey = 'status' . $key; // Membuat nama input seperti 'status0', 'status1'
            $status = $request->input($statusKey);
            if ($request->status.$key != null) {
                $exam = Examination::where('student_id', $request->id)->where('element_id', $value)->first();
                // dd($exam);
                if ($exam != null) {
                    Examination::where('student_id', $request->id)->where('element_id', $value)->update([
                        'status' => $status,
                    ]);
                }else{
                    // dd($exam);
                    Examination::create([
                        'exam_date' => now(),
                        'student_id' => $request->id,
                        'assessor_id' => Auth::user()->assessor->id,
                        'standard_id' => $request->standard_id,
                        'element_id' => $value,
                        'status' => $status,
                        'comments' => '-'
                    ]);
                }
            }
        }
        return back();
    }
}
