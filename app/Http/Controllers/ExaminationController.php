<?php

namespace App\Http\Controllers;

use App\Models\CompetencyStandard;
use App\Models\Examination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExaminationController extends Controller
{
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
}
