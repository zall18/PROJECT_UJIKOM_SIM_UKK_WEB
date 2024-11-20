<?php

// namespace App\Imports;

// use App\Models\Student;
// use App\Models\User;
// use Illuminate\Support\Facades\Hash;
// use Maatwebsite\Excel\Concerns\ToModel;

// class UserImport implements App\Imports\ToModel
// {
//     public function model(array $rows)
//     {
//         foreach ($rows as $index => $row) {
//             // Lewati baris header (jika ada)
//             if ($index === 0) {
//                 continue;
//             }

//             // Simpan data ke tabel users
//             $user = User::create([
//                 'full_name' => $row[0],
//                 'username' => $row[1],
//                 'email' => $row[2],
//                 'password' => Hash::make("smkypc"),
//                 'phone' => $row[3],
//                 'role' => 'student',

//             ])->id;

//             // Simpan data ke tabel students dengan relasi ke user
//             Student::create([
//                 'nisn' => $row[4],
//                 'grade_level' => $row[5],
//                 'major_id' => $row[6],
//                 'user_id' => $user
//             ]);
//         }
//     }
// }
