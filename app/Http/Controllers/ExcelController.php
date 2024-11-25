<?php

namespace App\Http\Controllers;

use App\Models\CompetencyElement;
use App\Models\CompetencyStandard;
use App\Models\Examination;
use App\Models\Major;
use App\Models\Student;
use App\Models\User;
use Auth;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Hash;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ExcelController extends Controller
{
    public function generateReportData(Request $request)
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
                'student_nisn' => $exams->first()->student->nisn,
                'elements' => $elementsStatus,
                'final_score' => $finalScore,
                'status' => $status
            ];
        });

        // dd($data['students']);
        return $data;
    }

    public function exportReport(Request $request)
    {
        $data = $this->generateReportData($request);
        $fileName = 'exam_report.xlsx';

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser($fileName);

        // Gaya header
        $headerStyle = (new StyleBuilder())->setFontBold()->build();

        // Header
        $header = ['Student ID', 'Student Name', 'Student NISN'];
        foreach ($data['elements'] as $element) {
            $header[] = "Element {$element->criteria}";
        }
        $header[] = 'Final Score';
        $header[] = 'Status';

        $writer->addRow(WriterEntityFactory::createRowFromArray($header, $headerStyle));

        // Data siswa
        foreach ($data['students'] as $student) {
            $row = [
                $student['student_id'] ?? '-',
                $student['student_name'] ?? '-',
                $student['student_nisn'] ?? '-',
            ];
            foreach ($student['elements'] as $element) {
                $row[] = $element['status'] ?? 'N/A';
            }
            $row[] = $student['final_score'] ?? 0;
            $row[] = $student['status'] ?? 'Unknown';

            $writer->addRow(WriterEntityFactory::createRowFromArray($row));
        }

        $writer->close();
        return response()->json(['message' => 'File berhasil diunduh!']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        $file = $request->file('file');

        // Buat reader untuk membaca file Excel
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($file);

        // Deklarasikan variabel $invalidRows
        $invalidRows = [];
        $duplicateRows = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            $isFirstRow = true;
            foreach ($sheet->getRowIterator() as $index => $row) {
                if ($isFirstRow) {
                    $isFirstRow = false; // Lewati baris pertama (header)
                    continue;
                }

                $cells = $row->getCells();
                $email = $cells[1];
                $nisn = $cells[3];
                $majorId = $cells[5];
                $major = Major::find($majorId);

                if (!$major) {
                    $invalidRows[] = [
                        'row' => $index,
                        'major_id' => $majorId
                    ];
                    continue;
                }

                $isDuplicateEmail = User::where('email', $email)->exists();
                $isDuplicateNisn = Student::where('nisn', $nisn)->exists();

                if ($isDuplicateEmail || $isDuplicateNisn) {
                    $duplicateRows[] = [
                        'row' => $index,
                        'issue' => $isDuplicateEmail ? "Duplicate email: $email" : "Duplicate NISN: $nisn"
                    ];
                    continue;
                }

                // Simpan data ke tabel users
                $user = User::create([
                    'full_name' => $cells[0],
                    'username' => strtolower(str_replace(' ', '', $cells[0])),
                    'email' => $cells[1],
                    'password' => Hash::make("smkypc"),
                    'phone' => substr($cells[2], 0, 11),
                    'role' => 'student',
                    'is_active' => '1'
                ])->id;

                // Simpan data ke tabel students dengan relasi ke user
                Student::create([
                    'nisn' => substr($cells[3], 0, ),
                    'grade_level' => $cells[4],
                    'major_id' => $cells[5],
                    'user_id' => $user
                ]);
            }
        }

        // Tampilkan alert warning jika ada baris yang tidak valid

        $reader->close();

        if (!empty($invalidRows)) {
            Alert::warning('Warning', 'Some rows were skipped due to invalid major_id.');
        } else if (!empty($duplicateRows)) {
            Alert::warning('Warning', 'Some rows were skipped due to duplicate data.');
        } else {
            Alert::success('Student', 'Success to import from excel!');
        }


        return redirect('/student/managment');
    }


}
