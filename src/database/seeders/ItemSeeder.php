<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('items')->insert([
            [
                'seller_id' => 1,
                'title' => '腕時計',
                'price' => 15000,
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'title' => 'HDD',
                'price' => 5000,
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'title' => '玉ねぎ3束',
                'price' => 300,
                'brand_name' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'title' => '革靴',
                'price' => 4000,
                'brand_name' => null,
                'description' => 'クラシックなデザインの革靴',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'title' => 'ノートPC',
                'price' => 45000,
                'brand_name' => null,
                'description' => '高性能なノートパソコン',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'title' => 'マイク',
                'price' => 8000,
                'brand_name' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'title' => 'ショルダーバッグ',
                'price' => 3500,
                'brand_name' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'title' => 'タンブラー',
                'price' => 500,
                'brand_name' => 'なし',
                'description' => '使いやすいタンブラー',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'title' => 'コーヒーミル',
                'price' => 4500,
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'title' => 'メイクセット',
                'price' => 2500,
                'brand_name' => null,
                'description' => '便利なメイクアップセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
