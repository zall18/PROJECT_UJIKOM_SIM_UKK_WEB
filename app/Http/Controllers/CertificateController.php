<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class CertificateController extends Controller
{
    public function generateCertificateView(Request $request)
    {
        if ($request->final_score > 60) {
            // Data yang akan diteruskan ke view
            // dd($request->input('standard_id'));
            $data = [
                'name' => $request->input('name', 'John Doe'),
                'program' => $request->input('program', 'Laravel Mastery Program'),
                'final_score' => $request->input('final_score', '0'),
                'date' => now()->format('F d, Y'),
                'elements' => Examination::where('standard_id', $request->input('standard_id'))->where('student_id', Auth::user()->student->id)->get()
            ];

            // Render PDF
            $pdf = Pdf::loadView('student.certificate', $data)->setPaper('a4', 'landscape');

            // Simpan atau tampilkan
            // return $pdf->download('certificate.pdf'); // Untuk download
            return $pdf->stream(); // Untuk ditampilkan di browser
        } else {
            Alert::error('Failed to generate certificate', 'You final score must upper 60');
            return back();
        }
    }
    public function generateCertificateDownload(Request $request)
    {
        if ($request->final_score > 60) {
            // Data yang akan diteruskan ke view
            // dd($request->input('standard_id'));
            $data = [
                'name' => $request->input('name', 'John Doe'),
                'program' => $request->input('program', 'Laravel Mastery Program'),
                'final_score' => $request->input('final_score', '0'),
                'date' => now()->format('F d, Y'),
                'elements' => Examination::where('standard_id', $request->input('standard_id'))->where('student_id', Auth::user()->student->id)->get()
            ];

            // Render PDF
            $pdf = Pdf::loadView('student.certificate', $data)->setPaper('a4', 'landscape');

            // Simpan atau tampilkan
            return $pdf->download($request->program . ' Certificate.pdf'); // Untuk download
            // return $pdf->stream(); // Untuk ditampilkan di browser
        } else {
            Alert::error('Failed to generate certificate', 'You final score must upper 60');
            return back();
        }
    }
}
