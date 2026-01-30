<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Condition;


class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('conditions')->insert([
            ['condition_name' => '良好', 'sort_order' => 1],
            ['condition_name' => '目立った傷や汚れなし', 'sort_order' => 2],
            ['condition_name' => 'やや傷や汚れあり', 'sort_order' => 3],
            ['condition_name' => '状態が悪い', 'sort_order' => 4],
        ]);
    }
}
