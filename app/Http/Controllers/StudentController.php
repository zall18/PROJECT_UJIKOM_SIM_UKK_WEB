<?php

namespace App\Http\Controllers;

use App\Models\CompetencyStandard;
use App\Models\Examination;
use App\Models\Major;
use App\Models\Student;
use App\Models\User;
use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\Common\Creator\ReaderFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class StudentController extends Controller
{
    public function index()
    {
        $data['students'] = Student::all();
        $data['active'] = 'student';
        return view('admin.studentManage', $data);
    }

    public function studentImport()
    {
        $data['active'] = 'student';
        $data['majors'] = Major::all();
        return view('admin.studentImport', $data);
    }



    public function dashboard(Request $request)
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
            $status = $finalScore >= 90 ? 'Competent' : 'Not Competent';
            return [
                'unit_title' => $standard->unit_title,
                'status' => $status,
                'final_score' => $finalScore,
            ];
        });

        $notCompetentCount = 0;
        foreach ($statusSummary as $key => $item) {
            if ($item['status'] == 'Not Competent') {
                $notCompetentCount++;
            }
        }
        $data['notCompetentCount'] = $notCompetentCount;
        $data['standards'] = $standards;
        $data['active'] = 'dashboard';
        $data['statusSummary'] = $statusSummary;

        return view('student.dashboard', $data);

    }

    public function studentProfile(Request $request)
    {

        $data['user'] = User::where('id', Auth::user()->id)->first();
        $data['majors'] = Major::all();
        $data['active'] = 'user';
        return view('student.profile', $data);

    }
}
