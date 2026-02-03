<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Eski adminni o'chirib, yangidan toza yaratamiz
        User::where('email', 'admin@gmail.com')->delete();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin12345'), // YANGI PAROL: admin12345
        ]);
    }
}