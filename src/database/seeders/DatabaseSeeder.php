<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ConditionSeeder::class,
            CategorySeeder::class,
            ItemSeeder::class,
            CategoryItemSeeder::class,
            PurchaseSeeder::Class,
            LikeSeeder::Class,
        ]);
    }
}
