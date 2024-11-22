<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Student;
use App\Models\User;
use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\Common\Creator\ReaderFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class StudentController extends Controller
{
    public function index(){
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

    public function import(Request $request)
    {
        // // Validasi file
        // $request->validate([
        //     'file' => 'required|mimes:xlsx,xls,csv',
        // ]);

        // // Proses import menggunakan Laravel Excel
        // Excel::import(new UserImport, $request->file('file'));

        // return redirect()->back()->with('success', 'Data berhasil diimpor!');
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        $file = $request->file('file');

        // Buat reader untuk membaca file Excel
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($file);

        foreach ($reader->getSheetIterator() as $sheet) {
            $isFirstRow = true;
            foreach ($sheet->getRowIterator() as $index => $row)
             {
                if ($isFirstRow) {
                    $isFirstRow = false; // Lewati baris pertama (header)
                    continue;
                }
                $cells = $row->getCells();

                if ($index === 0) {
                    continue;
                }

                // Simpan data ke tabel users
                $user = User::create([
                    'full_name' => $cells[0],
                    'username' => $cells[1],
                    'email' => $cells[2],
                    'password' => Hash::make("smkypc"),
                    'phone' => $cells[3],
                    'role' => 'student',
                    'is_active' => '1'
                ])->id;

                // Simpan data ke tabel students dengan relasi ke user
                Student::create([
                    'nisn' => $cells[4],
                    'grade_level' => $cells[5],
                    'major_id' => $cells[6],
                    'user_id' => $user
                ]);
            }
        }

        $reader->close();

        Alert::success('Student', 'Success to import from excel!');

        return redirect('/student/managment');

    }
}
