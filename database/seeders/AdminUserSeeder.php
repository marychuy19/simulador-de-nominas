<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'adminstrador2026@utecan.edu.mx'],
            [
                'name' => 'Admin',
                'password' => Hash::make('adminUtecan2026'),
                'role' => 'admin',
            ]
        );
    }
}
