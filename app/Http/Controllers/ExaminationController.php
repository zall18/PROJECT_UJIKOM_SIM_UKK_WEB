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

    public function result(Request $request)
    {
        $id = Auth::user()->assessor->id; // Pastikan relasi 'assessor' didefinisikan di model User
        // Ambil daftar semua standar kompetensi terkait assessor
        $standars = CompetencyStandard::with('major') // Pastikan relasi 'major' ada di model CompetencyStandar
            ->where('assessor_id', $id)
            ->get();

        // Ambil standar_id dari request, atau gunakan default 1 jika kosong
        $standar_id = $request->input('standar_id', 1); // Default ke 1 jika tidak ada input

        // Validasi standar ID
        $standard = CompetencyStandard::where('id', $standar_id)->with('competency_elements')->first();

        if (!$standard) {
            return back()->with('error', 'Standar kompetensi tidak ditemukan.');
        }

        // Mendapatkan data ujian berdasarkan standar yang dipilih
        $examinations = Examination::where('standard_id', $standar_id)->with('student.user')->get();

        // Kelompokkan berdasarkan student_id
        $students = $examinations->groupBy('student_id')->map(function ($exams) use ($standard) {
            $totalElements = $standard->competency_elements->count();
            $completedElements = $exams->where('status', 1)->unique('element_id')->count();

            $finalScore = $totalElements > 0 ? round(($completedElements / $totalElements) * 100) : 0;
            $status = $finalScore >= 90 ? 'Competent' : 'Not Competent';

            return [
                'student_id' => $exams->first()->student_id,
                'student_name' => $exams->first()->student->user->full_name,
                'final_score' => $finalScore,
                'status' => $status,
            ];
        });

        $active = 'result';

        // Debug untuk melihat hasil
        // dd($students);

        // Mengirim data ke view
        return view('assessor.examResultStudent', compact('standard', 'students', 'standars', 'active'));
    }

    public function fetchExamResult($standardId)
    {
        $standard = CompetencyStandard::withCount('competency_elements')->findOrFail($standardId);
        $examinations = Examination::where('standard_id', $standardId)->get();

        $students = $examinations->groupBy('student_id')->map(function ($exams) use ($standard) {
            $totalElements = $standard->competency_elements_count;
            $completedElements = $exams->where('status', 1)->count();

            $finalScore = $totalElements > 0 ? round(($completedElements / $totalElements) * 100) : 0;
            $status = $finalScore >= 75 ? 'Competent' : 'Not Competent';

            return [
                'student_id' => $exams->first()->student_id,
                'student_name' => $exams->first()->student->user->full_name,
                'final_score' => $finalScore,
                'status' => $status,
            ];
        })->values();

        return response()->json(['students' => $students]);
    }

    //Show exam result report from competency's assessor
    public function report(Request $request)
    {
        $data['elements'] = CompetencyElement::where('competency_standard_id', $request->id)->get();
        $data['standard'] = CompetencyStandard::where('id', $request->id)->first();
        $data['standards'] = CompetencyStandard::with('major')->where('assessor_id', Auth::user()->assessor->id)->get();
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

    public function fetchReport($standardId)
    {
        $standard = CompetencyStandard::with('competency_elements')->find($standardId);
        $examinations = Examination::where('standard_id', $standardId)->get();
        $elements = CompetencyElement::where('competency_standard_id', $standardId)->get();
        $students = $examinations->groupBy('student_id')->map(function ($exams) use ($standard) {
            $totalElements = $standard->competency_elements->count();
            $completedElements = $exams->where('status', 1)->count();
            $finalScore = round(($completedElements / $totalElements) * 100);
            $status = $finalScore >= 75 ? 'Competent' : 'Not Competent';

            $elementsStatus = $standard->competency_elements->map(function ($element) use ($exams) {
                $exam = $exams->firstWhere('element_id', $element->id);
                return [
                    'status' => $exam ? ($exam->status == 1 ? 'Competent' : 'Not Competent') : 'Not Evaluated',
                    'comments' => $exam ? $exam->comments : '-'
                ];
            });

            return [
                'student_id' => $exams->first()->student_id,
                'student_name' => $exams->first()->student->user->full_name,
                'elements' => $elementsStatus,
                'final_score' => $finalScore,
                'status' => $status,
            ];
        });

        return response()->json(['students' => $students, 'elements' => $elements]);
    }


    //show all exam result
    public function reportAdmin(Request $request)
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
            $status = $this->conversi($finalScore);
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
        $data['students'] = Student::where('major_id', $standard->major_id)->get();
        $data['elements'] = CompetencyElement::where('competency_standard_id', $request->standard_id)->get();
        $data['examinations'] = Examination::where('standard_id', $request->standard_id)->where('student_id', $request->id)->get();
        $data['active'] = 'student';
        return view('assessor.assesmentStudentExam', $data);
    }
    public function fetchassessmentStudentExam(Request $request)
    {
        $standard = CompetencyStandard::find($request->standard_id);
        $student = Student::with('user')->find($request->id); // Pastikan relasi 'user' di-load
        $students = Student::where('major_id', $standard->major_id)->with('user')->get();
        $elements = CompetencyElement::where('competency_standard_id', $request->standard_id)->get();
        $examinations = Examination::where('standard_id', $request->standard_id)
            ->where('student_id', $request->id)
            ->get();

        if (!$student) {
            return response()->json([
                'error' => 'Student not found',
            ], 404);
        }

        return response()->json([
            'standard' => $standard,
            'student' => $student,
            'students' => $students,
            'elements' => $elements,
            'examinations' => $examinations,
        ]);
    }



    public function grading(Request $request)
    {
        // dd($request->status);

        $elements = $request->elements;

        foreach ($elements as $key => $value) {
            // dd($request->id);
            // dd([$request->status0, $request->status1]);
            $statusKey = 'status' . $key; // Membuat nama input seperti 'status0', 'status1'
            $status = $request->input($statusKey);
            if ($request->status . $key != null) {
                $exam = Examination::where('student_id', $request->id)->where('element_id', $value)->first();
                // dd($exam);
                if ($exam != null) {
                    Examination::where('student_id', $request->id)->where('element_id', $value)->update([
                        'status' => $status,
                    ]);
                } else {
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
        return response()->json([
            'success' => true,
            'message' => 'Grading saved successfully!',
        ]);
    }

    private function conversi($grade)
    {
        if ($grade >= 91 && $grade <= 100) {
            return 'Very Competent';
        } else if ($grade >= 75 && $grade <= 90) {
            return 'Competent';
        } else if ($grade >= 61 && $grade <= 74) {
            return 'Quite Competent';
        } else if ($grade <= 60) {
            return 'Not Competent';
        }
    }

    public function resultStudent(Request $request)
    {
        // Ambil student yang sedang login
        $student = Auth::user()->student;

        // Ambil semua competency standard berdasarkan jurusan student
        $standards = CompetencyStandard::where('major_id', $student->major_id)->with('competency_elements')->get();

        // Hasil akhir untuk menyimpan status setiap competency standard
        $statusSummary = $standards->map(function ($standard) use ($student) {
            // Ambil semua examination terkait competency standard ini dan student login
            $examinations = Examination::where('standard_id', $standard->id)
                ->where('student_id', $student->id)
                ->get();

            // Hitung jumlah elemen dan status
            $totalElements = $standard->competency_elements->count();
            $completedElements = $examinations->where('status', 1)->unique('element_id')->count();

            // Hitung nilai akhir
            $finalScore = $totalElements > 0 ? round(($completedElements / $totalElements) * 100) : 0;

            // Tentukan status
            $status = $this->conversi($finalScore);

            return [
                'unit_title' => $standard->unit_title,
                'status' => $status,
                'final_score' => $finalScore,
            ];
        });

        $data['active'] = 'examResult';
        $data['statusSummary'] = $statusSummary;
        $data['student'] = $student;

        return view('student.examResult', $data);
    }





}
