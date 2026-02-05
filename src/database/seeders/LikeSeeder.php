<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('likes')->insert([
            [
                'user_id'    => 1,
                'item_id'    => 8,
                'created_at' => now(),
            ],
            [
                'user_id'    => 1,
                'item_id'    => 9,
                'created_at' => now(),
            ],
        ]);
    }
}
