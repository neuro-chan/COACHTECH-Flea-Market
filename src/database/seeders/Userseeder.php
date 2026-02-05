<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Userseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    DB::table('users')->insert([
        [
            'name' => 'testuser01',
            'email' => 'test01@example.com',
            'password' => Hash::make('testuser01'),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'testuser02',
            'email' => 'test02@example.com',
            'password' => Hash::make('testuser02'),
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
}
}
