<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('category_item')->insert([
            [
                'item_id'     => 1,
                'category_id' => 1,
                'created_at'  => now(),
            ],
            [
                'item_id'     => 1,
                'category_id' => 2,
                'created_at'  => now(),
            ],
            [
                'item_id'     => 1,
                'category_id' => 3,
                'created_at'  => now(),
            ],
            [
                'item_id'     => 1,
                'category_id' => 4,
                'created_at'  => now(),
            ],
        ]);
    }
}
