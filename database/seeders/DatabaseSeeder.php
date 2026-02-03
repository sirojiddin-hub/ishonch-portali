<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Agar bu email bilan foydalanuvchi bo'lmasa, yangi admin yaratamiz
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // LOGIN SHU BO'LADI
            [
                'name' => 'Adminstrator',
                'password' => Hash::make('admin123'), // PAROL SHU BO'LADI
                'role' => 'admin', // Agar loyihangizda role ustuni bo'lsa
            ]
        );
    }
}