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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class StudentController extends Controller
{
    public function index()
    {
        $data['students'] = Student::with('user')->get();   
        $data['active'] = 'student';
        return view('admin.studentManage', $data);
    }

    public function studentImport()
    {
        $data['active'] = 'student';
        $data['majors'] = Major::all();
        return view('admin.studentImport', $data);
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
            $status = $this->conversi($finalScore);
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
    public function profileUpdate(Request $request)
    {

        $data['user'] = User::where('id', Auth::user()->id)->first();
        $data['majors'] = Major::all();
        $data['active'] = 'user';
        return view('student.editProfile', $data);

    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'username' => 'required',
            'email' => 'required',
            'phone' => 'required'
        ]);

        User::where('id', $request->id)->update([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password != null ? bcrypt($request->password) : DB::raw('password'),
            'phone' => $request->phone
        ]);

        return redirect('/student/profile');

    }
}
