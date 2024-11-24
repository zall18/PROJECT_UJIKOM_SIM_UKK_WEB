<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CertificateController extends Controller
{
    public function generateCertificate(Request $request)
    {
        if ($request->final_score > 60) {
            // Data yang akan diteruskan ke view
            $data = [
                'name' => $request->input('name', 'John Doe'),
                'program' => $request->input('program', 'Laravel Mastery Program'),
                'final_score' => $request->input('final_score', '0'),
                'date' => now()->format('F d, Y')
            ];

            // Render PDF
            $pdf = Pdf::loadView('student.certificate', $data);

            // Simpan atau tampilkan
            // return $pdf->download('certificate.pdf'); // Untuk download
            return $pdf->stream(); // Untuk ditampilkan di browser
        } else {
            Alert::error('Failed to generate certificate', 'You final score must upper 60');
            return back();
        }
    }
}
