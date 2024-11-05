<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            "full_name" => "Muhamad Rizal Fikri",
            "username" => "rizal12",
            "email" => "muhamadrizalf1112@gmail.com",
            "password" => bcrypt("1234"),
            "phone" => "087893364214",
            "role" => "admin",
            "is_aactive" => 1
        ]);



        // Student::create([

        // ])
    }
}
