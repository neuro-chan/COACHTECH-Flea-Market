<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('purchases')->insert([
            'item_id' => 1,
            'buyer_id'=> 1,
            'shipping_postal_code'=> 1360072,
            'shipping_address'=> '東京都港区お台場フジテレビ',
            'amount'      => 15000,
            'payment_method'=> 1,
            'created_at' => now(),
        ]);
    }
}
